<?php
namespace PHPHadoop\Kernel\Providers;

use PHPHadoop\Kernel\Exceptions\Exception;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Elasticsearch\ClientBuilder;

/**
 * ElasticSearchServiceProvider
 *
 * @date      2019-06-17
 * @package   PHPHadoop\Kernel\Providers
 * @version   1.0
 */
class ElasticSearchServiceProvider implements ServiceProviderInterface
{
    /**
     * register
     *
     * @param \Pimple\Container $pimple
     */
    public function register(Container $pimple)
    {
        $pimple['elasticsearch'] = function (Container $app) {
            $config = $app->offsetGet('config');
            if (!isset($config['es_config']) && empty($config['es_config'])) {
                throw new Exception('please set es_config !');
            }
            $esConfig = $config['es_config'];
            $builder  = ClientBuilder::create();
            $builder->setHosts(array (
                sprintf('%s://%s:%s@%s:%s',
                    $esConfig['protocol'],
                    $esConfig['user'],
                    $esConfig['password'],
                    $esConfig['host'],
                    $esConfig['port']
                )
            ));
            return $builder->build();

        };
    }
}