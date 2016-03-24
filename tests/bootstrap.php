<?php

function get_config($key) {
    static $configs = null;
    if (is_null($configs)) {
        $configs = require(__DIR__ . '/config.php');
    }

    return isset($configs[$key]) ? $configs[$key] : null;
}

$loader = require dirname(__DIR__) . '/vendor/autoload.php';
$loader->add('tests\\', dirname(__DIR__));

use LumengPHP\Db\Misc\ShortcutFunctionHelper;
use LumengPHP\Db\ConnectionManager;

require(ShortcutFunctionHelper::getPath());

$dbConfigs = get_config('database');
$connManager = new ConnectionManager($dbConfigs);
