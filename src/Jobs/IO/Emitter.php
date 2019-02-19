<?php
/*
 * This file is part of the m9rco/hadoop-php
 *
 * (c) m9rco https://github.com/m9rco
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace PHPHadoop\Jobs\IO;

use PHPHadoop\Jobs\IO\Data\Output;
use Pimple\Container;

/**
 * Emitter
 *
 * @date      2019-02-18
 * @package   PHPHadoop\Jobs\IO
 * @version   1.0
 */
class Emitter
{
    /**
     * @var \Phadoop\MapReduce\Job\IO\Data\Output
     */
    private $last;

    /**
     * Emitter constructor.
     *
     * @param \Pimple\Container $app
     */
    public function __construct(Container $app)
    {

    }

    /**
     * @param string $key
     * @param mixed  $value
     * @return void
     */
    public function emit($key, $value)
    {
        $output = Output::create($key, $value);
        echo $output . "\n";

        $this->last = $output;
    }

    /**
     * @return \Phadoop\MapReduce\Job\IO\Data\Output
     */
    public function getLast()
    {
        return $this->last;
    }
}