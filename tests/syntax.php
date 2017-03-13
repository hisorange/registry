<?php
use hisorange\Registry\Pool;
use hisorange\Registry\Manager;
use hisorange\Registry\Entity;

require dirname(__DIR__) . '/autoloader.php';

class_alias('hisorange\Registry\Pool', 'Registry');

$pool = new Pool;
$ss   = new Manager;
$ss->registerAsGlobal();
$pool['global']['test'] = 3;
$ss['test'] = 'reved1';
$ss['test'] = 'reved2';
$ss['test'] = 'reved3';
$ss['test'] = 'reved4';

var_dump($ss['test']);

$ss->getEntity('test')->rollback();
var_dump($ss->test()->getValue());

$ss->getEntity('test')->rollback();
var_dump($pool['global']['test']);

$ss->test->rollback();
var_dump($pool['global']['test']);


var_dump($ss->test);
