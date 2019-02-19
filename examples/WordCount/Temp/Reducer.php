#!/usr/bin/env php
<?php

defined('DEBUG') || define('DEBUG', true);

require '/Users/pushaowei/develop/_practice/hadoop-php/vendor/autoload.php';
require '/Users/pushaowei/develop/_practice/hadoop-php/examples/WordCount/Reducer.php';

$worker = new Reducer((new PHPHadoop\Jobs\Application());
$worker->handle();
