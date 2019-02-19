<?php
namespace PHPHadoop\Jobs\Contracts;

/**
 * WorkerInterface
 *
 * @date      2019-02-18
 * @version   1.0
 */
interface WorkerInterface
{
    /**
     * @return void
     */
    public function handle();
}