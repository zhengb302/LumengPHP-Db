<?php

$configs = array(
    /* 数据库配置 */
    'dbConfigs' => array(
        //groupName => groupConfigs
        'group1' => array(
            'class' => '\LumengPHP\Db\ConnectionGroups\SimpleConnectionGroup',
            //数据库类型：mysql、pgsql、oracle、mssql等
            'type' => 'mysql',
            //表前缀，如：bbs_
            'tablePrefix' => '',
            //是否是默认组
            'isDefault' => true,
            //数据库配置
            'host' => '',
            'port' => 3306,
            'database' => '',
            'username' => '',
            'password' => '',
        ),
        'group2' => array(
            'class' => '\LumengPHP\Db\ConnectionGroups\MasterSlaveConnectionGroup',
            //数据库类型：mysql、pgsql、oracle、mssql等
            'type' => 'mysql',
            //表前缀，如：bbs_
            'tablePrefix' => '',
            //是否是默认组
            'isDefault' => false,
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
    /*
     * LumengPHP项目代码目录配置，用于autoloader，如：
     * dirname(dirname(__DIR__)) . '/LumengPHP-dev'
     */
    'LumengPHPRoot' => '',
);

$localConfigs = file_exists(__DIR__ . '/local.config.php') ?
        require(__DIR__ . '/local.config.php') : array();

return array_merge($configs, $localConfigs);
