<?php
/*
 * This file is part of the m9rco/hadoop-php
 *
 * (c) m9rco https://github.com/m9rco
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace PHPHadoop\Jobs\Worker;

use PHPHadoop\Jobs\IO\Emitter;
use Pimple\Container;
use PHPHadoop\Jobs\Contracts\WorkerInterface;

/**
 * Mapper
 *
 * @date      2019-02-18
 * @package   PHPHadoop\Jobs\Worker
 * @version   1.0
 */
abstract class Mapper implements WorkerInterface
{
    /**
     * @var
     */
    protected $app;

    /**
     * @var \Closure
     */
    protected $callback;

    /**
     * Command constructor.
     *
     * @param \Pimple\Container $app
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    /**
     * map
     *
     * @param \PHPHadoop\Jobs\IO\Emitter $emitter
     * @param                            $key
     * @param                            $value
     * @return mixed
     */
    abstract protected function map(Emitter $emitter, $key, $value);

    /**
     * handle
     *
     * @return void
     */
    public function handle()
    {
        while (($input = $this->app->offsetGet('reader')->read()) !== false) {
            $this->map(
                $this->app->offsetGet('emitter'),
                $input->getKey(),
                $input->getValue()
            );
        }
    }
}