<?php
/*
 * This file is part of the m9rco/hadoop-php
 *
 * (c) m9rco https://github.com/m9rco
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace PHPHadoop\Jobs\IO\Data;

use PHPHadoop\Jobs\IO\Data;

/**
 * Output
 *
 * @date      2019-02-18
 * @version   1.0
 */
class Output extends Data
{
    /**
     * create
     *
     * @static
     * @param $key
     * @param $value
     * @return \PHPHadoop\Jobs\IO\Data\Output
     */
    public static function create($key, $value)
    {
        return new self($key, $value);
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->key . self::$delimiter . self::getEncoder()->encode($this->value);
    }
}