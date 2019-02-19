<?php

/*
 * This file is part of the m9rco/hadoop-php
 *
 * (c) m9rco https://github.com/m9rco
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace PHPHadoop\Jobs\Traits;

use PHPHadoop\Jobs\Contracts\WorkerInterface;

/**
 * Trait Worker
 *
 * @package PHPHadoop\Jobs\Traits
 */
trait Worker
{
    /**
     * setCallback
     *
     * @param $callback
     * @return mixed
     */
    public function setCallback(\Closure $callback)
    {
        return $this->callback = $callback;
    }

    /**
     * getEmitter
     *
     * @static
     * @return \PHPHadoop\Jobs\IO\Emitter
     */
    public function getEmitter()
    {
        return $this->app->offsetGet('emitter');
    }

    /**
     * getDebugger
     *
     * @return \PHPHadoop\Jobs\Worker\Debugger
     */
    public function getDebugger()
    {
        return $this->app->offsetGet('debugger');

    }

    /**
     * @return bool
     */
    public function isInDebugMode()
    {
        return defined('DEBUG') && DEBUG;
    }

    /**
     * isEqualTo
     *
     * @param \PHPHadoop\Jobs\Contracts\WorkerInterface $worker
     * @return bool
     */
    public function isEqualTo(WorkerInterface $worker)
    {
        return get_class($this) === get_class($worker);
    }

    /**
     * @static
     * @param string $key
     * @param mixed  $value
     * @return void
     */
    public function emit($key, $value)
    {

    }
}