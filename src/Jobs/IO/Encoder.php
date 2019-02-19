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
 * Encoder
 *
 * @date      2019-02-18
 * @package   PHPHadoop\Jobs\IO
 * @version   1.0
 */
class Encoder
{
    /**
     * @param mixed $data
     * @return string
     */
    public static function encode($data)
    {
        return json_encode($data);
    }

    /**
     * @param string $data
     * @return mixed
     */
    public static function decode($data)
    {
        return json_decode(trim($data), true);
    }
}