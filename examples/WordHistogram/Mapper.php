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
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function map(\PHPHadoop\Jobs\IO\Emitter $emitter, $key, $value){
        $content = strtolower(trim($value));
        $words = preg_split('/\W/', $content, 0, PREG_SPLIT_NO_EMPTY);

        foreach ($words as $word) {
            $emitter->emit($word, 1);
        }
    }

}
