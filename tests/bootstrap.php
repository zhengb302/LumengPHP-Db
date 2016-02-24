<?php

function get_config($key) {
    static $configs = null;
    if (is_null($configs)) {
        $configs = require(__DIR__ . '/config.php');
    }

    return isset($configs[$key]) ? $configs[$key] : null;
}

$loader = require dirname(__DIR__) . '/vendor/autoload.php';
$loader->add('LumengPHP\\', get_config('LumengPHPRoot'));

\LumengPHP\Db\Misc\ShortcutFunctionsLoader::loadShortcutFunctions();

$dbConfigs = get_config('dbConfigs');
$connManager = \LumengPHP\Db\ConnectionManager::getConnectionManager();
$connManager->loadDbConfigs($dbConfigs);
