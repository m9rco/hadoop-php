<?php
namespace PHPHadoop\Kernel\Providers;

use PHPHadoop\Kernel\Exceptions\Exception;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use  Illuminate\Database\Capsule\Manager;

/**
 * DatabasesServiceProvider
 *
 * @date      2019-06-17
 * @package   PHPHadoop\Kernel\Providers
 * @version   1.0
 */
class DatabasesServiceProvider implements ServiceProviderInterface
{
    /**
     * register
     *
     * @param \Pimple\Container $pimple
     */
    public function register(Container $pimple)
    {
        $pimple['databases'] = function (Container $app) {
            $config = $app->offsetGet('config');
            if (!isset($config['db_config']) && empty($config['db_config'])) {
                throw new Exception('please set db_config');
            }
            $dbName  = $config['db_config']['database'];
            $capsule = new Manager;
            $capsule->addConnection($config['db_config'], $dbName);
            $capsule->getConnection($dbName)->enableQueryLog();
            $capsule->setAsGlobal();
            $capsule->bootEloquent();
            return $capsule;

        };
    }
}