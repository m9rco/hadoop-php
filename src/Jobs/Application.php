<?php
/*
 * This file is part of the m9rco/hadoop-php
 *
 * (c) m9rco https://github.com/m9rco
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace PHPHadoop\Jobs;

use PHPHadoop\Kernel\ServiceContainer;

/**
 * Class Application
 *
 * @property \PHPHadoop\Jobs\MapReduce\MapReduce $mr
 * @date      2019-02-18
 * @version   1.0
 */
class Application extends ServiceContainer
{
    /**
     * @var array
     */
    protected $providers = array (
        Worker\ServiceProvider::class,
        Generator\ServiceProvider::class,
        IO\ServiceProvider::class,
        MapReduce\ServiceProvider::class,
    );
}