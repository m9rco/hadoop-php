<?php
/*
 * This file is part of the m9rco/hadoop-php
 *
 * (c) m9rco https://github.com/m9rco
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace PHPHadoop\Kernel;

use PHPHadoop\Kernel\Exceptions\UnexpectedValueException;
use Pimple\Container;

/**
 * FileSystem
 *
 * @date      2019-02-18
 * @package   PHPHadoop\Kernel
 * @version   1.0
 */
class FileSystem
{
    /**
     * @var Command
     */
    protected $command;

    /**
     * Command constructor.
     *
     * @param \Pimple\Container $app
     */
    public function __construct(Container $app)
    {
        $this->command = $app['command'];
    }

    /**
     * exec
     *
     * @param $cmd
     * @param $args
     * @return mixed
     */
    private function exec($cmd, $args)
    {
        return $this->command->exec("dfs -$cmd", $args);
    }

    /**
     * execHDFS
     *
     * @param $cmd
     * @param $args
     * @return mixed
     */
    private function execHDFS($cmd, $args)
    {
        return $this->command->execHDFS("-$cmd", $args);
    }

    /**
     * @param string $content Text content or path to file in local file system
     * @param string $filePath
     * @return mixed
     * @throws \Exception
     */
    public function writeToFile($content, $filePath)
    {
        if (is_file($content)) {
            return $this->exec('put', array ($content, $filePath));
        }

        if (!is_string($content) && method_exists($content, '__toString')) {
            $content = $content->__toString();
        }

        if (is_string($content)) {
            return $this->command->exec(
                'printf "' .
                str_replace('"', '\"', str_replace('\\', '\\\\', $content)) .
                '" | %hadoop% dfs -put', $filePath);
        }

        throw new UnexpectedValueException(sprintf('Invalid content type "%s"',
            is_object($content) ? get_class($content) : gettype($content)));
    }

    /**
     * Moves file from local file system to the hadoop file system
     *
     * @param string $localFilePath
     * @param string $hdfsFilePath
     * @return mixed
     */
    public function moveFromLocal($localFilePath, $hdfsFilePath)
    {
        return $this->exec('moveFromLocal', array ($localFilePath, $hdfsFilePath));
    }

    /**
     * @param string $hdfsFilePath
     * @param string $localFilePath
     * @return mixed
     */
    public function copyToLocal($hdfsFilePath, $localFilePath)
    {
        return $this->execHDFS('get', array ($hdfsFilePath, $localFilePath));
    }

    /**
     * @param string $hdfsPath
     * @param bool   $recursive
     * @return mixed
     */
    public function remove($hdfsPath, $recursive = false)
    {
        return $this->execHDFS($recursive ? 'rm -r' : 'rm', $hdfsPath);
    }

    /**
     * @param string $hdfsFilePath
     * @return string
     */
    public function displayFileContent($hdfsFilePath)
    {
        return $this->execHDFS('cat', $hdfsFilePath);
    }
}