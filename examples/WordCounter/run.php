<?php

require dirname(__FILE__) . '/../../vendor/autoload.php';
require dirname(__FILE__) . '/Mapper.php';
require dirname(__FILE__) . '/Reducer.php';

$options = array (
    'bin'            => 'hadoop-x', // which hdfs
    'hdfs_bin'       => 'hdfs-x', // which hdfs
    'streaming_bin'  => '/usr/local/Cellar/hadoop/3.1.1/libexec/libexec/tools/hadoop-streaming.sh',
    'job_name'       => 'default',
    'output'         => 'mysql', // mysql | file
    'output_path'    => './',
    'databases'      => array (
        'host'      => '127.0.0.1',
        'port'      => '3306',
        'driver'    => 'mysql',
        'database'  => 'hadoop',
        'username'  => 'root',
        'password'  => '',
        'charset'   => 'utf8',
        'collation' => 'utf8_general_ci',
        'prefix'    => 't_',
        'strict'    => false,
        'engine'    => null,
    ),
    'elastic_search' => array (
        'host'   => '',
        'scroll' => '30s',
        'index'  => '',
        'body'   => array (
            'from' => 0,
            'size' => 9999,
        ),
    )
);

define('DEBUG', true);
$app = PHPHadoop\Factory::Jobs($options);
$mr  = $app->mr;
$mr->setMapper(new Mapper($app));
$mr->setReducer(new Reducer($app));
try {
    $mr->clearData()
       ->addTask('Hello World')
       ->addTask('Hello Hadoop')
       ->putResultsTo('cache/result.log')
       ->run();
} catch (\PHPHadoop\Kernel\Exceptions\UnexpectedValueException $e) {
} catch (ReflectionException $e) {
}