<?php
require __DIR__ . '/../../vendor/autoload.php';

/**
 * Mapper
 *
 * @date      2019-02-19
 * @version   1.0
 */
class Mapper extends PHPHadoop\Jobs\Worker\Mapper
{
    /**
     * map
     *
     * @param \PHPHadoop\Jobs\IO\Emitter $emitter
     * @param                            $key
     * @param                            $value
     * @return mixed|void
     */
    public function map(\PHPHadoop\Jobs\IO\Emitter $emitter, $key, $value)
    {
        $emitter->emit('wordsNumber', count(preg_split('/\s+/', trim((string)$value))));
    }
}