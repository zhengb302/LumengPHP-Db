<?php

$configs = [
    //数据库配置
    'database' => [
        //connectionName => connectionConfig
        //第一个连接，第一个数据库连接为默认连接
        'db1' => [
            'pdoProvider' => LumengPHP\Db\Connection\SimplePDOProvider::class,
            //数据库类型：mysql
            'type' => 'mysql',
            //表前缀，如：bbs_
            'tablePrefix' => 'bbs_',
            //数据库字符集
            'charset' => 'utf8',
            //数据库配置
            'host' => 'dbhost',
            'port' => 3306,
            'dbName' => 'bbs',
            'username' => 'bbs',
            'password' => 'bbs',
        ],
        //第二个连接
        'db2' => [
            'pdoProvider' => LumengPHP\Db\Connection\MasterSlavePDOProvider::class,
            //数据库类型：mysql
            'type' => 'mysql',
            //表前缀，如：bbs_
            'tablePrefix' => 'bbs_',
            //数据库字符集
            'charset' => 'utf8',
            //数据库服务器列表，第一个为master服务器，剩下的为从服务器。
            //必须确保服务器列表有不少于1台的服务器。
            'servers' => [
                [
                    'host' => 'dbhost1',
                    'port' => 3306,
                    'dbName' => 'bbs',
                    'username' => '',
                    'password' => '',
                ],
                [
                    'host' => 'dbhost2',
                    'port' => 3306,
                    'dbName' => 'bbs',
                    'username' => '',
                    'password' => '',
                ],
            ],
        ],
    ],
];

$localConfigs = file_exists(__DIR__ . '/local.config.php') ?
        require(__DIR__ . '/local.config.php') : [];

return array_merge($configs, $localConfigs);
