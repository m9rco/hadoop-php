<?php
require __DIR__ . '/../../vendor/autoload.php';

class Reducer extends PHPHadoop\Jobs\Worker\Reducer
{
    /**
     * @param string       $key
     * @param \Traversable $values
     * @return int
     */
    public function reduce(\PHPHadoop\Jobs\IO\Emitter $emitter, $key, \Traversable $values)
    {

        $sum = 0;
        foreach ($values as $count) {
            $sum += (int)$count;
        }

        $emitter->emit($key, $sum);
    }

}
