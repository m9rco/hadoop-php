<?php
require dirname(__FILE__) . '/../../vendor/autoload.php';
require dirname(__FILE__) . '/Mapper.php';
require dirname(__FILE__) . '/Reducer.php';

$options = array (
    'bin'           => 'hadoop-x', // which hdfs
    'php'           => '/usr/local/php7/bin/php',
    'streaming_bin' => '/usr/local/Cellar/hadoop/3.1.1/libexec/libexec/tools/hadoop-streaming.sh',
    'job_name'      => 'default',
);

$app = PHPHadoop\Factory::Jobs($options);
$mr  = $app->mr;
$mr->setMapper(new Mapper($app));
$mr->setReducer(new Reducer($app));
try {
    $mr->clearData()
       ->addTask('Hello World')
       ->addTask('Hello Hadoop')
       ->putResultsTo('Temp/Results.txt')
       ->run();

    echo $mr->getLastResults();

} catch (\PHPHadoop\Kernel\Exceptions\UnexpectedValueException $e) {
} catch (ReflectionException $e) {
}

