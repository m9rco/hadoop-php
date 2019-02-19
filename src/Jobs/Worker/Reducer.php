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
use PHPHadoop\Jobs\Traits\Worker;
use PHPHadoop\Jobs\IO\InputIterator;
use Pimple\Container;
use PHPHadoop\Jobs\Contracts\WorkerInterface;

/**
 * Reducer
 *
 * @date      2019-02-18
 * @package   PHPHadoop\Jobs\Worker
 * @version   1.0
 */
abstract class Reducer implements WorkerInterface
{
    use Worker;

    /**
     * @var
     */
    protected $app;


    /**
     * @var \Closure
     */
    protected $callback;

    /**
     * Reducer constructor.
     *
     * @param \Pimple\Container $app
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    /**
     * reduce
     *
     * @param \PHPHadoop\Jobs\IO\Emitter $emitter
     * @param                            $key
     * @param \Traversable               $values
     * @return mixed
     */
    abstract protected function reduce(Emitter $emitter, $key, \Traversable $values);

    /**
     * @return void
     */
    public function handle()
    {
        $inputIterator = new InputIterator($this->app->offsetGet('reader'));
        while (!$inputIterator->isIterated()) {
            $this->callback->call(
                $this->app->offsetGet('emitter'),
                $inputIterator->key(),
                $inputIterator
            );
            while ($inputIterator->valid()) {
                $inputIterator->next();
            }
            $inputIterator->reset();
        }
    }
}