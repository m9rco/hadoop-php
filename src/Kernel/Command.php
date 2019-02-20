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

use Pimple\Container;

/**
 * Command
 *
 * @package  PHPHadoop\Kernel
 * @uses     description
 * @version  1.0
 */
class Command
{
    /**
     * @var \Pimple\Container
     */
    protected $app;

    /**
     * @var string
     */
    protected $hadoopBin;
    /**
     * @var string
     */
    protected $hdfsBin;

    /**
     * Command constructor.
     *
     * @param \Pimple\Container $app
     */
    public function __construct(Container $app)
    {
        $this->app       = $app;
        $this->hadoopBin = $this->app['config']['bin'];
        $this->hdfsBin   = $this->app['config']['hdfs_bin'];
    }

    /**
     * @return string
     */
    public function getHadoopPath()
    {
        return $this->hadoopBin;
    }

    /**
     * exec
     *
     * @param $cmd
     * @param $args
     * @return bool|string
     */
    public function exec($cmd, $args)
    {
        echo '-----------------------------------' . PHP_EOL;
        echo "{$this->prepareCmd($cmd)} {$this->prepareCmdArgs($args)}" . PHP_EOL;
        echo '-----------------------------------' . PHP_EOL;
        return system("{$this->prepareCmd($cmd)} {$this->prepareCmdArgs($args)}");
    }

    /**
     * execHDFS
     *
     * @param $cmd
     * @param $args
     * @return bool|string
     */
    public function execHDFS($cmd, $args)
    {
        echo '-----------------------------------' . PHP_EOL;
        echo "{$this->prepareHDFSCmd($cmd)} {$this->prepareCmdArgs($args)}" . PHP_EOL;
        echo '-----------------------------------' . PHP_EOL;
        return system("{$this->prepareHDFSCmd($cmd)} {$this->prepareCmdArgs($args)}");
    }

    /**
     * prepareHDFSCmd
     *
     * @param $cmd
     * @return mixed|string
     */
    protected function prepareHDFSCmd($cmd)
    {
        $result = (string)$cmd;
        if (strpos($result, '%hdfs%') === false) {
            $result = "{$this->hdfsBin} $result";
        } else {
            $result = str_replace('%hdfs%', $this->hdfsBin, $result);
        }

        return $result;
    }

    /**
     * @param string $cmd
     * @return string
     */
    private function prepareCmd($cmd)
    {
        $result = (string)$cmd;
        if (strpos($result, '%hadoop%') === false) {
            $result = "{$this->hadoopBin} $result";
        } else {
            $result = str_replace('%hadoop%', $this->hadoopBin, $result);
        }

        return $result;
    }

    /**
     * @param string|array $args
     * @return string
     */
    private function prepareCmdArgs($args)
    {
        if (!is_array($args)) {
            return (string)$args;
        }

        $result = '';
        $files  = array ();
        foreach ($args as $arg => $value) {
            if (!is_int($arg)) {
                $arg    = (string)$arg;
                $result .= " -$arg";
            }
            $value  = (string)$value;
            $result .= " $value";
            if (in_array($arg, array ('mapper', 'reducer'))) {
                array_push($files, $value);
            }
        }

        foreach ($files as $item) {
            $result .= " -file {$item}";
        }


        return trim($result);
    }
}