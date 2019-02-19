<?php
/*
 * This file is part of the m9rco/hadoop-php
 *
 * (c) m9rco https://github.com/m9rco
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace PHPHadoop\Jobs\Generator;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * ServiceProvider
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
        !isset($app['code_generator']) && $app['code_generator'] = function ($app) {
            return new CodeGenerator($app);
        };
    }
}