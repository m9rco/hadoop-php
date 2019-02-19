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

use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * ServiceProvider
 *
 * @date      2019-02-18
 * @package   PHPHadoop\Jobs\IO
 * @version   1.0
 */
class ServiceProvider implements ServiceProviderInterface
{
    /**
     * register
     *
     * @param \Pimple\Container $app
     * {@inheritdoc}.
     */
    public function register(Container $app)
    {
        !isset($app['emitter']) && $app['emitter'] = function ($app) {
            return new Emitter($app);
        };
        !isset($app['reader']) && $app['reader'] = function ($app) {
            return new Reader($app);
        };
    }
}