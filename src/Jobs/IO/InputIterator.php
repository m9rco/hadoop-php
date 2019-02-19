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
 * InputIterator
 *
 * @package  PHPHadoop\Jobs\IO
 * @uses     description
 * @version  1.0
 */
class InputIterator implements \Iterator
{
    /**
     * @var  \PHPHadoop\Jobs\IO\Reader $reader
     */
    private $reader;

    /**
     * @var string
     */
    private $previousKey;

    /**
     * @var string
     */
    private $currentKey;

    /**
     * @var mixed
     */
    private $currentValue;

    /**
     * InputIterator constructor.
     *
     * @param \PHPHadoop\Jobs\IO\Reader $reader
     */
    public function __construct(Reader $reader)
    {
        $this->setReader($reader);
        $this->next();
        $this->reset();
    }

    /**
     * setReader
     *
     * @param \PHPHadoop\Jobs\IO\Reader $reader
     * @return $this
     */
    private function setReader(Reader $reader)
    {
        $this->reader = $reader;
        return $this;
    }

    /**
     * Allows iterating for the current key
     *
     * @return void
     */
    public function reset()
    {
        $this->previousKey = $this->currentKey;
    }

    /**
     * Checks if input is processed
     *
     * @return bool
     */
    public function isIterated()
    {
        return is_null($this->currentKey);
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->currentValue;
    }

    /**
     * Returns current value
     *
     * @return mixed
     */
    public function current()
    {
        return $this->currentValue;
    }

    /**
     * Returns current key
     *
     * @return string
     */
    public function key()
    {
        if (is_null($this->currentKey)) {
            return null;
        }

        return (string)$this->currentKey;
    }

    /**
     * @return void
     */
    public function next()
    {
        $this->currentKey   = null;
        $this->currentValue = null;

        if (false !== $input = $this->reader->read()) {
            $this->currentKey   = $input->getKey();
            $this->currentValue = $input->getValue();
        }
    }

    /**
     * @return void
     */
    public function rewind() { }

    /**
     * Iterator is valid until we read another key
     *
     * @return bool
     */
    public function valid()
    {
        return $this->currentKey == $this->previousKey;
    }
}