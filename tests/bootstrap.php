<?php

$loader = require dirname(__DIR__) . '/vendor/autoload.php';
$loader->add('tests\\', dirname(__DIR__));

use LumengPHP\Db\Misc\ShortcutFunctionHelper;
use LumengPHP\Db\ConnectionManager;

require(ShortcutFunctionHelper::getPath());

$configs = require(__DIR__ . '/config.php');
$connectionConfigs = $configs['connections'];
$connManager = new ConnectionManager($connectionConfigs);
