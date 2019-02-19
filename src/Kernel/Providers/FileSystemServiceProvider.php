<?php
namespace PHPHadoop\Kernel\Providers;

/*
 * This file is part of the m9rco/hadoop-php
 *
 * (c) m9rco https://github.com/m9rco
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace PHPHadoop\Kernel\Providers;

use PHPHadoop\Kernel\FileSystem;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * FileSystemServiceProvider
 *
 * @package  PHPHadoop\Kernel\Providers
 * @uses     description
 * @version  1.0
 */
class FileSystemServiceProvider implements ServiceProviderInterface
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
        $pimple['file_system'] = function ($app) {
            return new FileSystem($app);
        };
    }
}