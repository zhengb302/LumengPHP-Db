<?php

$configs = array(
    //数据库连接配置
    //第一个数据库连接为默认连接
    'connections' => array(
        //connectionName => connectionConfig
        //first connection
        'db1' => array(
            'class' => 'LumengPHP\Db\Connection\SimpleConnection',
            //数据库类型：mysql、pgsql、sqlsrv等
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
        ),
        //second connection
        'db2' => array(
            'class' => 'LumengPHP\Db\Connection\MasterSlaveConnection',
            //数据库类型：mysql、pgsql、sqlsrv等
            'type' => 'mysql',
            //表前缀，如：bbs_
            'tablePrefix' => 'bbs_',
            //数据库字符集
            'charset' => 'utf8',
            //数据库服务器列表，第一个为master服务器，剩下的为从服务器
            'servers' => array(
                array(
                    'host' => 'dbhost1',
                    'port' => 3306,
                    'dbName' => 'bbs',
                    'username' => '',
                    'password' => '',
                ),
                array(
                    'host' => 'dbhost2',
                    'port' => 3306,
                    'dbName' => 'bbs',
                    'username' => '',
                    'password' => '',
                ),
            ),
        ),
    ),
);

$localConfigs = file_exists(__DIR__ . '/local.config.php') ?
        require(__DIR__ . '/local.config.php') : array();

return array_merge($configs, $localConfigs);
