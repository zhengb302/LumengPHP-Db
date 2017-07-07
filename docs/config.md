## 配置

### 连接配置

#### 单数据库连接

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

#### 一主多从

目前只支持mysql一主多从的模式，主数据库主要负责写入，多个从数据库分担读的压力。配置示例：
```php
return [
    'database' => [
        //连接名称 => 连接配置
        'db1' => [
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

#### 多数据库连接

有时候需要读写不同的数据库，例如业务数据和日志数据存放在不同的数据库用以优化架构及分担压力，这个时候就需要多数据库连接。
配置示例：
```php
return [
    'database' => [
        //连接名称 => 连接配置
        'bbsMain' => [
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
        //连接名称 => 连接配置
        'bbsLog' => [
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

默认情况下是使用第一个连接，如果要使用其他连接，则需要覆盖Model类的\$**connectionName**属性，以提供欲使用的连接名称：
```php
class UserModel extends Model {
    //...

    protected $connectionName = 'bbsLog';

    //..
}
```