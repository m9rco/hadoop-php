<?php
namespace PHPHadoop\Jobs\IO;

use Pimple\Container;

/**
 * Reader
 *
 * @date      2019-02-18
 * @package   PHPHadoop\Jobs\IO
 * @version   1.0
 */
class Reader
{
    /**
     * Emitter constructor.
     *
     * @param \Pimple\Container $app
     */
    public function __construct(Container $app)
    {

    }

    /**
     * Read
     *
     * @static
     * @return bool|\PHPHadoop\Jobs\IO\Data\Input
     */
    public static function read()
    {
        $line = fgets(STDIN);
        if ($line !== false) {
            return Data\Input::createFromString(trim($line));
        }

        return false;
    }
}