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

use PHPHadoop\Jobs\Contracts\WorkerInterface;
use PHPHadoop\Jobs\IO\Data\Output;

/**
 * Debugger
 *
 * @date      2019-02-18
 * @package   PHPHadoop\Jobs\Worker
 * @version   1.0
 */
class Debugger
{
    /**
     * logEmit
     *
     * @static
     * @param \PHPHadoop\Jobs\Contracts\WorkerInterface $worker
     * @param \PHPHadoop\Jobs\IO\Data\Output            $output
     */
    public static function logEmit(WorkerInterface $worker, Output $output)
    {
        error_log(get_class($worker) . ': ' . $output);
    }
}