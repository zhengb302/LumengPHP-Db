## 开始入门

### 前情提要

假设正在开发一个简单的bbs系统。数据库名称“bbsdb”，目前有“用户表”(bbs_user)和“帖子表”(bbs_post)两个数据库表。
点击查看[数据库表结构](/tests/resources/database.sql)。

### 安装

#### 系统要求

* PHP >= 5.6
* PDO扩展

#### 安装LumengPHP-Db

1，创建项目根目录：
```bash
cd /home/lumeng/webdev
mkdir tiny-bbs
cd tiny-bbs
```

2，创建并进入项目根目录之后，使用composer下载并安装LumengPHP-Db。在项目根目录下执行以下命令：
```bash
composer require lumeng/lumeng-php-db
```
即可完成LumengPHP-Db的安装。

3，LumengPHP-Db通常作为一个独立的库集成进第三方框架，以使其具备方便快捷的操作数据库的能力。
在项目中集成LumengPHP-Db：
```php
<?php
//composer autoloader
//require __DIR__ . '/vendor/autoload.php';

use LumengPHP\Db\ConnectionManager;

//$configs = require(somewhere . '/config.php');
$connectionConfigs = $configs['database'];
ConnectionManager::create($connectionConfigs);
```

### 配置

#### 单数据库服务器

支持的数据库类型有：mysql、pgsql、sqlsrv等，配置示例：
```php
return [
    'database' => [
        //连接名称 => 连接配置
        'db1' => [
            'class' => LumengPHP\Db\Connection\SimpleConnection::class,
            //数据库类型：mysql、pgsql、sqlsrv等
            'type' => 'mysql',
            //表前缀，如：bbs_
            'tablePrefix' => '_',
            //数据库字符集
            'charset' => 'utf8',
            //数据库配置
            'host' => '',
            'port' => '',
            'dbName' => '',
            'username' => '',
            'password' => '',
        ],
    ]
];
```

#### 一主多从

目前只支持mysql一主多从的模式，主数据库主要负责写入，多个从数据库分担读的压力。配置示例：
```php
return [
    'database' => [
        //连接名称 => 连接配置
        'db2' => [
            'class' => LumengPHP\Db\Connection\MasterSlaveConnection::class,
            //数据库类型：mysql
            'type' => 'mysql',
            //表前缀，如：bbs_
            'tablePrefix' => '',
            //数据库字符集
            'charset' => 'utf8',
            //数据库服务器列表，第一个为master服务器，剩下的为从服务器
            'servers' => [
                [
                    'host' => 'dbhost1',
                    'port' => 3306,
                    'dbName' => '',
                    'username' => '',
                    'password' => '',
                ],
                [
                    'host' => 'dbhost2',
                    'port' => 3306,
                    'dbName' => '',
                    'username' => '',
                    'password' => '',
                ],
            ],
        ],
    ]
];
```

#### 多服务器连接

