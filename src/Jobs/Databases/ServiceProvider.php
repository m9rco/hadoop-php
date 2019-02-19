<?php
/*
 * This file is part of the m9rco/hadoop-php
 *
 * (c) m9rco https://github.com/m9rco
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace PHPHadoop\Jobs\Databases;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * DatabasesProvider
 *
 * @date      2019-02-18
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
        !isset($app['databases']) && $app['databases'] = function ($app) {
            var_dump(1);
            die;
        };
    }
}