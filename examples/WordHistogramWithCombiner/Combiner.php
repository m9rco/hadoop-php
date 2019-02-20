<?php
require __DIR__ . '/../../vendor/autoload.php';

/**
 * We could simply use the reducer class as combiner
 * This class is needed only for debug logs
 */
class Combiner extends \Phadoop\MapReduce\Job\Worker\Reducer
{
}

