<?php
require __DIR__ . '/../../vendor/autoload.php';

/**
 * Reducer
 *
 * @date      2019-02-19
 * @version   1.0
 */
class Reducer extends PHPHadoop\Jobs\Worker\Reducer
{
    /**
     * reduce
     *
     * @param \PHPHadoop\Jobs\IO\Emitter $emitter
     * @param                            $key
     * @param \Traversable               $values
     * @return mixed|void
     */
    public function reduce(\PHPHadoop\Jobs\IO\Emitter $emitter, $key, \Traversable $values)
    {
        $result = 0;
        foreach ($values as $value) {
            $result += (int)$value;
        }

        $emitter->emit($key, $result);}

}