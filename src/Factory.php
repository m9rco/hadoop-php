<?php
namespace PHPHadoop;

/*
 * This file is part of the m9rco/hadoop-php
 *
 * (c) m9rco https://github.com/m9rco
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

/**
 * @method static \PHPHadoop\Jobs\Application    Jobs(array $config)
 * @package  PHPHadoop
 * @uses     description
 * @version  1.0
 */
class Factory
{
    /**
     * @param string $name
     * @param array  $config
     * @return \PHPHadoop\Kernel\ServiceContainer
     */
    public static function make($name, array $config)
    {
        $namespace   = Kernel\Support\Str::studly($name);
        $application = "\\PHPHadoop\\{$namespace}\\Application";
        return new $application($config);
    }

    /**
     * Dynamically pass methods to the application.
     *
     * @param string $name
     * @param array  $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        return self::make($name, ...$arguments);
    }
}