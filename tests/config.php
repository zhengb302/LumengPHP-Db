<?php

$configs = array(
    //数据库配置
    //第一个数据库连接为默认连接
    'database' => array(
        //connectionName => connectionConfig
        //first connection
        'db1' => array(
            'class' => 'LumengPHP\Db\Connection\SimpleConnection',
            //表前缀，如：bbs_
            'tablePrefix' => '',
            //数据库字符集
            'charset' => 'utf8',
            //数据库配置
            'dsn' => 'mysql:host=127.0.0.1;dbname=bbs',
            'username' => '',
            'password' => '',
        ),
        //second connection
        'db2' => array(
            'class' => 'LumengPHP\Db\Connection\MasterSlaveConnection',
            //表前缀，如：bbs_
            'tablePrefix' => '',
            //数据库字符集
            'charset' => 'utf8',
            //数据库服务器列表，第一个为master服务器，剩下的为从服务器
            'servers' => array(
                array(
                    'dsn' => 'mysql:host=127.0.0.1;dbname=bbs',
                    'username' => '',
                    'password' => '',
                ),
                array(
                    'dsn' => 'mysql:host=127.0.0.1;dbname=bbs',
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
