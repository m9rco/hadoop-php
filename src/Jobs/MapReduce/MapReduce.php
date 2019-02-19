<?php
/*
 * This file is part of the m9rco/hadoop-php
 *
 * (c) m9rco https://github.com/m9rco
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace PHPHadoop\Jobs\MapReduce;

use PHPHadoop\Jobs\IO\Data\Output;
use PHPHadoop\Jobs\Worker\Mapper;
use PHPHadoop\Jobs\Worker\Reducer;
use PHPHadoop\Kernel\Exceptions\InvalidArgumentException;
use PHPHadoop\Kernel\Exceptions\UnexpectedValueException;
use Pimple\Container;

/**
 * MapReduce
 *
 * @date      2019-02-18
 * @package   PHPHadoop\Jobs\MapReduce
 * @version   1.0
 */
class MapReduce
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var mixed
     */
    private $command;

    /**
     * @var mixed
     */
    private $fileSystem;
    /**
     * @var
     */
    private $mapper;

    /**
     * @var
     */
    private $reducer;

    /**
     * @var
     */
    private $combiner;

    /**
     * @var array
     */
    private $streamingOptions;

    /**
     * @var
     */
    private $cacheDir;

    /**
     * @var int
     */
    private $taskCounter;

    /**
     * @var
     */
    private $resultsFileLocalPath;

    /**
     * @var
     */
    private $lastResults;

    /**
     * @var
     */
    private $codeGenerator;

    /**
     * @var \Pimple\Container
     */
    protected $app;

    /**
     * MapReduce constructor.
     *
     * @param \Pimple\Container $app
     * @throws \PHPHadoop\Kernel\Exceptions\InvalidArgumentException
     */
    public function __construct(Container $app)
    {
        $this->app         = $app;
        $config            = $app->offsetGet('config');
        $this->command     = $app->offsetGet('command');
        $this->fileSystem  = $app->offsetGet('file_system');
        $this->taskCounter = 0;
        $this->name        = $config['job_name'];
        $this->setCacheDir($config['cache_dir']);
        $this->streamingOptions = array ();
    }

    /**
     * description
     *
     * @param $cacheDir
     * @return $this
     * @throws \PHPHadoop\Kernel\Exceptions\InvalidArgumentException
     */
    private function setCacheDir($cacheDir)
    {
        if (!file_exists($cacheDir)) {
            mkdir($cacheDir, 0777);
        } else if (!is_dir($cacheDir)) {
            throw new InvalidArgumentException(sprintf('"%s" is not a directory', $cacheDir));
        }

        $this->cacheDir = realpath((string)$cacheDir);
        return $this;
    }

    /**
     * setMapper
     *
     * @param \PHPHadoop\Jobs\Worker\Mapper $mapper
     * @return $this
     */
    public function setMapper(Mapper $mapper)
    {
        $this->mapper = $mapper;
        return $this;
    }

    /**
     * setReducer
     *
     * @param \PHPHadoop\Jobs\Worker\Reducer $reducer
     * @return $this
     */
    public function setReducer(Reducer $reducer)
    {
        $this->reducer = $reducer;
        return $this;
    }

    /**
     * hasCombiner
     *
     * @return bool
     */
    private function hasCombiner()
    {
        return !is_null($this->combiner);
    }

    /**
     * clearData
     *
     * @return $this
     */
    public function clearData()
    {
        $this->fileSystem->remove($this->name, true);
        return $this;
    }

    /**
     * addTask
     *
     * @param      $key
     * @param null $data
     * @return $this
     */
    public function addTask($key, $data = null)
    {
        if (is_null($data)) {
            $data = $key;
            $key  = null;
        }

        $this->taskCounter++;
        $taskHdfsFilePath = "{$this->getHdfsTasksDir()}/{$this->taskCounter}.tsk";
        if (is_file($data)) {
            $this->fileSystem->moveFromLocal($this->prepareTaskFromFile($key, $data), $taskHdfsFilePath);
        } else {
            $this->fileSystem->writeToFile(Output::create($key, $data), $taskHdfsFilePath);
        }

        return $this;
    }

    /**
     * prepareTaskFromFile
     *
     * @param $key
     * @param $localFilePath
     * @return string
     */
    private function prepareTaskFromFile($key, $localFilePath)
    {
        $tasksDir = "{$this->cacheDir}/Tasks";
        if (!is_dir($tasksDir)) {
            mkdir($tasksDir);
            chmod($tasksDir, 0766);
        }

        $taskLocalFilePath = "$tasksDir/{$this->taskCounter}.tsk";
        file_put_contents($taskLocalFilePath, Output::create($key, file_get_contents($localFilePath)));

        return $taskLocalFilePath;
    }

    /**
     * getHdfsTasksDir
     *
     * @return string
     */
    private function getHdfsTasksDir()
    {
        return $this->name . '/tasks';
    }

    /**
     * getHdfsResultsDir
     *
     * @return string
     */
    private function getHdfsResultsDir()
    {
        return $this->name . '/results';
    }

    /**
     * putResultsTo
     *
     * @param $localFilePath
     * @return $this
     */
    public function putResultsTo($localFilePath)
    {
        $this->resultsFileLocalPath = (string)$localFilePath;
        return $this;
    }

    /**
     * setStreamingOption
     *
     * @param $option
     * @param $value
     * @return $this
     */
    public function setStreamingOption($option, $value)
    {
        $this->streamingOptions[(string)$option] = (string)$value;
        return $this;
    }

    /**
     * run
     *
     * @return $this
     * @throws \PHPHadoop\Kernel\Exceptions\UnexpectedValueException
     * @throws \ReflectionException
     */
    public function run()
    {
        $this->assertCacheDirIsSet();
        $this->assertMapperIsSet();
        $this->assertReducerIsSet();

        $this->getCodeGenerator()->generateScript($this->mapper, $this->cacheDir . '/Mapper.php');
        $this->getCodeGenerator()->generateScript($this->reducer, $this->cacheDir . '/Reducer.php');

        $jobParams = array ($this->getHadoopStreamingJarPath(), '-D mapred.output.compress=false');
        foreach ($this->streamingOptions as $option => $value) {
            $jobParams[] = "-D $option=$value";
        }

        $jobParams = array_merge($jobParams, array (
            'input'   => $this->name . '/tasks/*',
            'output'  => $this->name . '/results',
            'mapper'  => $this->cacheDir . '/Mapper.php',
            'reducer' => $this->cacheDir . '/Reducer.php',
        ));

        if ($this->hasCombiner()) {
            $this->getCodeGenerator()->generateScript($this->combiner, $this->cacheDir . '/Combiner.php');
            $jobParams['combiner'] = $this->cacheDir . '/Combiner.php';
        }

        $this->command->exec('jar', $jobParams);
        $this->rememberResults();

        return $this;
    }

    /**
     * getCodeGenerator
     *
     * @return \PHPHadoop\Jobs\Generator\CodeGenerator
     */
    private function getCodeGenerator()
    {
        if (is_null($this->codeGenerator)) {
            $this->codeGenerator = $this->app['code_generator'];
        }

        return $this->codeGenerator;
    }

    /**
     * assertCacheDirIsSet
     *
     * @throws \PHPHadoop\Kernel\Exceptions\UnexpectedValueException
     */
    private function assertCacheDirIsSet()
    {
        if (is_null($this->cacheDir)) {
            throw new UnexpectedValueException("Cache dir isn't set");
        }
    }

    /**
     * assertMapperIsSet
     *
     * @throws \PHPHadoop\Kernel\Exceptions\UnexpectedValueException
     */
    private function assertMapperIsSet()
    {
        if (is_null($this->mapper)) {
            throw new UnexpectedValueException("Mapper isn't set");
        }
    }

    /**
     * assertReducerIsSet
     *
     * @throws \PHPHadoop\Kernel\Exceptions\UnexpectedValueException
     */
    private function assertReducerIsSet()
    {
        if (is_null($this->reducer)) {
            throw new UnexpectedValueException("Reducer isn't set");
        }
    }

    /**
     * getHadoopStreamingJarPath
     *
     * @return string
     */
    private function getHadoopStreamingJarPath()
    {
        return $this->app->offsetGet('config')['streaming_bin'];
//       $streamingDirPath = "{$this->command->getHadoopPath()}/contrib/streaming";
//      return $streamingDirPath . '/' . system("ls $streamingDirPath | grep \"hadoop-streaming.*\.jar\"");
    }

    /**
     * getResultsFileHdfsPath
     *
     * @return string
     */
    private function getResultsFileHdfsPath()
    {
        return "{$this->getHdfsResultsDir()}/part-00000";
    }

    /**
     * rememberResults
     *
     * @return $this
     */
    private function rememberResults()
    {
        $resultsFile = $this->resultsFileLocalPath;
        if (is_null($resultsFile)) {
            $resultsFile = $this->cacheDir . '/Results.txt';
        }

        system("rm $resultsFile");
        $this->fileSystem->copyToLocal($this->getResultsFileHdfsPath(), $resultsFile);
        if (is_file($resultsFile)) {
            $this->lastResults = file_get_contents($resultsFile);
        }

        return $this;
    }

    /**
     * getLastResults
     *
     * @return mixed
     */
    public function getLastResults()
    {
        return $this->lastResults;
    }
}