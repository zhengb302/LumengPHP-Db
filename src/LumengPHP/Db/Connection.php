<?php

namespace LumengPHP\Db;

use \PDO;

/**
 * 数据库连接
 *
 * @author zhengluming <luming.zheng@baozun.cn>
 */
class Connection extends PDO {

    /**
     * 读操作
     */
    const OP_READ = 0;

    /**
     * 写操作
     */
    const OP_WRITE = 1;

    /**
     * 构造连接
     * @param string $type 数据库类型
     * @param array $config
     * @return Connection
     */
    public static function makeConnection($type, $config) {
        $lowerCaseType = strtolower($type);

        $conn = null;
        if ($lowerCaseType == 'mysql') {
            $dsn = "mysql:dbname={$config['database']};host={$config['host']}";
            $user = $config['username'];
            $password = $config['password'];

            $conn = new self($dsn, $user, $password);
        } elseif ($lowerCaseType == 'pgsql') {
            //@todo ...
        } elseif ($lowerCaseType == 'oracle') {
            //@todo ...
        } elseif ($lowerCaseType == 'mssql') {
            //@todo ...
        } else {
            //@todo ...
        }

        return $conn;
    }

}
