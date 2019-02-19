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
 * Input
 *
 * @date      2019-02-18
 * @version   1.0
 */
class Input extends Data
{
    /**
     * createFromString
     *
     * @static
     * @param $inputString
     * @return \PHPHadoop\Jobs\IO\Data\Input
     */
    public static function createFromString($inputString)
    {
        $inputStringParts = explode(self::$delimiter, trim($inputString));

        if (count($inputStringParts) == 1) {
            return new self(self::DEFAULT_KEY, self::getEncoder()->decode($inputStringParts[0]));
        }

        return new self($inputStringParts[0], self::getEncoder()->decode($inputStringParts[1]));
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
}