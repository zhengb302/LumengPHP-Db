<?php

$configs = array(
    //数据库配置
    //第一个数据库连接组为默认组
    'database' => array(
        //groupName => groupConfigs
        'group1' => array(
            'class' => '\LumengPHP\Db\ConnectionGroup\SimpleConnectionGroup',
            //数据库类型：mysql、pgsql、oracle、mssql等
            'type' => 'mysql',
            //表前缀，如：bbs_
            'tablePrefix' => '',
            //数据库配置
            'host' => '',
            'port' => 3306,
            'database' => '',
            'username' => '',
            'password' => '',
        ),
        'group2' => array(
            'class' => '\LumengPHP\Db\ConnectionGroup\MasterSlaveConnectionGroup',
            //数据库类型：mysql、pgsql、oracle、mssql等
            'type' => 'mysql',
            //表前缀，如：bbs_
            'tablePrefix' => '',
            //数据库服务器列表，第一个为master服务器，剩下的为从服务器
            'servers' => array(
                array(
                    'host' => '',
                    'port' => 3306,
                    'database' => '',
                    'username' => '',
                    'password' => '',
                ),
                array(
                    'host' => '',
                    'port' => 3306,
                    'database' => '',
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
