<?php
/*
 * This file is part of the m9rco/hadoop-php
 *
 * (c) m9rco https://github.com/m9rco
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace PHPHadoop\Kernel\Providers;

use PHPHadoop\Kernel\Command;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * CommandServiceProvider
 *
 * @date      2019-02-18
 * @package   PHPHadoop\Kernel\Providers
 * @version   1.0
 */
class CommandServiceProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given container.
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container $pimple A container instance
     */
    public function register(Container $pimple)
    {
        $pimple['command'] = function ($app) {
            return new Command($app);
        };
    }
}