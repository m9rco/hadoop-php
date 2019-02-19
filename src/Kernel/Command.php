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
     * Command constructor.
     *
     * @param \Pimple\Container $app
     */
    public function __construct(Container $app)
    {
        $this->app       = $app;
        $this->hadoopBin = $this->app['config']['bin'];
    }

    /**
     * @return string
     */
    public function getHadoopPath()
    {
        return $this->hadoopBin;
    }

    /**
     * @param string       $cmd
     * @param array|string $args
     * @return mixed
     */
    public function exec($cmd, $args)
    {
        return system("{$this->prepareCmd($cmd)} {$this->prepareCmdArgs($args)}");
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
        foreach ($args as $arg => $value) {
            if (!is_int($arg)) {
                $arg    = (string)$arg;
                $result .= " -$arg";
            }

            $value  = (string)$value;
            $result .= " $value";
        }

        return trim($result);
    }
}