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

//日志组件可选
$logger = new SomeLogger();

ConnectionManager::create($connectionConfigs, $logger);
```

### 配置

#### 配置连接

```php
return [
    'database' => [
        //连接名称 => 连接配置
        'db1' => [
            'class' => LumengPHP\Db\Connection\SimpleConnection::class,
            //数据库类型：mysql、pgsql、sqlsrv等
            'type' => 'mysql',
            //表前缀，如：bbs_
            'tablePrefix' => '',
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

更多连接配置，请查看[连接配置](config.md#连接配置)
