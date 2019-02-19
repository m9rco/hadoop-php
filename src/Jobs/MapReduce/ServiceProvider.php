<?php
/*
 * This file is part of the m9rco/hadoop-php
 *
 * (c) m9rco https://github.com/m9rco
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace PHPHadoop\Jobs\MapReduce;

use Pimple\Container;
use Pimple\ServiceProviderInterface;


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
        !isset($app['mr']) && $app['mr'] = function ($app) {
            return new MapReduce($app);
        };

    }
}