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

/**
 * Data
 *
 * @date      2019-02-18
 * @package   PHPHadoop\Jobs\IO
 * @version   1.0
 */
class Data
{
    /**
     * @var string
     */
    protected $key;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @const string
     */
    const DEFAULT_KEY = 0;

    /**
     * Default key-value delimiter for the Hadoop streaming
     * http://hadoop.apache.org/common/docs/r0.15.2/streaming.html
     *
     * @var string
     */
    protected static $delimiter = "\t";

    /**
     * @var \Phadoop\MapReduce\Job\IO\Encoder
     */
    private static $encoder;

    /**
     * @static
     * @param string $delimiter
     * @return void
     */
    public static function setDelimiter($delimiter)
    {
        self::$delimiter = (string)$delimiter;
    }

    /**
     * setEncoder(
     *
     * @static
     * @param \PHPHadoop\Jobs\IO\Encoder $encoder
     */
    public static function setEncoder(Encoder $encoder)
    {
        self::$encoder = $encoder;
    }

    /**
     * getEncoder
     *
     * @static
     * @return \Phadoop\MapReduce\Job\IO\Encoder|\PHPHadoop\Jobs\IO\Encoder
     */
    protected static function getEncoder()
    {
        if (is_null(self::$encoder)) {
            self::$encoder = new Encoder();
        }

        return self::$encoder;
    }

    /**
     * @param string $key
     * @param mixed  $value
     */
    protected function __construct($key, $value)
    {
        if (is_null($key)) {
            $key = self::DEFAULT_KEY;
        }

        $this->key   = (string)$key;
        $this->value = $value;
    }
}