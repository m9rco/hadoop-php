<?php

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . DIRECTORY_SEPARATOR . 'Mapper.php';
require __DIR__ . DIRECTORY_SEPARATOR . 'Reducer.php';

$options = array (
    'bin'            => '/usr/local/bin/hdfs', // which hdfs
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
       ->putResultsTo('Temp/result.log')
       ->run();
} catch (\PHPHadoop\Kernel\Exceptions\UnexpectedValueException $e) {
} catch (ReflectionException $e) {
}