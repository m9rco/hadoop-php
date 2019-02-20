<?php
namespace PHPHadoop\Jobs\Generator;

use Composer\Autoload\ClassLoader;
use PHPHadoop\Jobs\Contracts\WorkerInterface;
use Pimple\Container;
use Phar;
use FilesystemIterator;

/**
 * CodeGenerator
 *
 * @date      2019-02-18
 * @package   PHPHadoop\Jobs\CodeGenerator
 * @version   1.0
 */
class CodeGenerator
{
    /**
     * @var string
     */
    protected $rootDir;
    /**
     * @var string
     */
    protected $templatesPath;

    /**
     * @var string
     */
    protected $composerAutoload;

    /**
     * @var
     */
    protected $app;

    /**
     * CodeGenerator constructor.
     *
     * @param \Pimple\Container $app
     * @throws \ReflectionException
     */
    public function __construct(Container $app)
    {
        $this->app              = $app;
        $classReflection        = new \ReflectionClass(ClassLoader::class);
        $this->composerAutoload = dirname(dirname($classReflection->getFileName()))
                                  . DIRECTORY_SEPARATOR
                                  . 'autoload.php';
        $this->rootDir          = dirname(dirname($this->composerAutoload));
        $thisReflection         = new \ReflectionClass($this);
        $thisPath               = $thisReflection->getFileName();
        $this->templatesPath    = substr($thisPath, 0, strpos($thisPath, 'CodeGenerator.php'));
    }

    /**
     * generateScript
     *
     * @param \PHPHadoop\Jobs\Contracts\WorkerInterface $worker
     * @param                                           $outputFile
     * @throws \ReflectionException
     */
    public function generateScript(WorkerInterface $worker, $outputFile)
    {
        $script = file_get_contents("{$this->templatesPath}/Worker.template");

        $workerReflectionClass = new \ReflectionClass($worker);
        $workerClassName       = $workerReflectionClass->getShortName();
        $workerFileName        = $workerReflectionClass->getFileName();

        $script = str_replace('%DEBUG_TEMPLATE%', defined('DEBUG') && DEBUG ? 'true' : 'false', $script);
        $script = str_replace('%UniversalClassLoaderPath%', $this->composerAutoload, $script);
        $script = str_replace('%WorkerFilePath%', $workerFileName, $script);
        $script = str_replace('%ProjectWorkerClassName%', $workerClassName, $script);

        file_put_contents($outputFile, $script);
        $pharFile = str_replace('.php', '.phar', $outputFile);
        if (file_exists($pharFile)) {
            unlink($pharFile);
        }
        $file       = $workerClassName . '.phar';
        $phar       = new Phar(
            dirname($outputFile) . DIRECTORY_SEPARATOR . $file,
            FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::KEY_AS_FILENAME,
            $file
        );
        $outputFile = str_replace($this->rootDir, '', $outputFile);
        // 开始打包
        $phar->startBuffering();
        $phar->buildFromDirectory($this->rootDir, '/\.php$/');
        $stub = <<<EOF
#!/usr/bin/env php
<?php
Phar::mapPhar('{$file}');
require 'phar://{$file}{$outputFile}';
__HALT_COMPILER();
?>");
EOF;
        $phar->setStub($stub);
        $phar->compressFiles(Phar::GZ);
        $phar->stopBuffering();
        chmod($pharFile, 0770);
    }
}