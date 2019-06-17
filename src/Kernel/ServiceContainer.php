<?php
/*
 * This file is part of the m9rco/hadoop-php
 *
 * (c) m9rco https://github.com/m9rco
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace PHPHadoop\Kernel;

use PHPHadoop\Kernel\Providers\CommandServiceProvider;
use PHPHadoop\Kernel\Providers\ConfigServiceProvider;
use PHPHadoop\Kernel\Providers\DatabasesServiceProvider;
use PHPHadoop\Kernel\Providers\ElasticSearchServiceProvider;
use PHPHadoop\Kernel\Providers\FileSystemServiceProvider;
use Pimple\Container;

/**
 * Class ServiceContainer
 *
 * @package  PHPHadoop\Kernel
 * @uses     description
 * @version  1.0
 */
class ServiceContainer extends Container
{

    /**
     * @var string
     */
    protected $id;

    /**
     * @var array
     */
    protected $providers = [];

    /**
     * @var array
     */
    protected $defaultConfig = [];

    /**
     * @var array
     */
    protected $userConfig = [];

    /**
     * Constructor.
     *
     * @param array       $config
     * @param array       $prepends
     * @param string|null $id
     */
    public function __construct(array $config = [], array $prepends = [], string $id = null)
    {
        $this->registerProviders($this->getProviders());

        parent::__construct($prepends);

        $this->userConfig = $config;

        $this->id = $id;

    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id ?? $this->id = md5(json_encode($this->userConfig));
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        $base = [
            'bin'           => 'hadoop',
            'php'           => '/usr/local/bin/php',
            'streaming_bin' => '/usr/bin/hadoop/hadoop-streaming.jar',
            'cache_dir'     => trim(`pwd`) . DIRECTORY_SEPARATOR . "cache",
            'db_conf'       => array (),
            'es_conf'       => array (),
            'output'        => null,
            'input'         => null,
        ];

        return array_replace_recursive($base, $this->defaultConfig, $this->userConfig);
    }

    /**
     * Return all providers.
     *
     * @return array
     */
    public function getProviders()
    {
        return array_merge([
            CommandServiceProvider::class,
            ConfigServiceProvider::class,
            FileSystemServiceProvider::class,
            DatabasesServiceProvider::class,
            ElasticSearchServiceProvider::class,
        ], $this->providers);
    }

    /**
     * @param string $id
     * @param mixed  $value
     */
    public function rebind($id, $value)
    {
        $this->offsetUnset($id);
        $this->offsetSet($id, $value);
    }

    /**
     * Magic get access.
     *
     * @param string $id
     * @return mixed
     */
    public function __get($id)
    {
        return $this->offsetGet($id);
    }

    /**
     * Magic set access.
     *
     * @param string $id
     * @param mixed  $value
     */
    public function __set($id, $value)
    {
        $this->offsetSet($id, $value);
    }

    /**
     * @param array $providers
     */
    public function registerProviders(array $providers)
    {
        foreach ($providers as $provider) {
            parent::register(new $provider());
        }
    }
}
