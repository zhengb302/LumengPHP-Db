<?php

namespace LumengPHP\Db;

use PDO;

/**
 * 数据库连接
 *
 * @author zhengluming <luming.zheng@baozun.cn>
 */
class Connection {

    /**
     * 读操作
     */
    const OP_READ = 0;

    /**
     * 写操作
     */
    const OP_WRITE = 1;

    /**
     * @var PDO PDO实例
     */
    private $pdo;

    /**
     * 构造连接
     * @param string $type 数据库类型
     * @param array $config 数据库配置
     */
    public function __construct($type, $config) {
        $lowerCaseType = strtolower($type);

        if ($lowerCaseType == 'mysql') {
            $dsn = "mysql:dbname={$config['database']};host={$config['host']}";
            $user = $config['username'];
            $password = $config['password'];

            $this->pdo = new PDO($dsn, $user, $password);
        } elseif ($lowerCaseType == 'pgsql') {
            //@todo ...
        } elseif ($lowerCaseType == 'oracle') {
            //@todo ...
        } elseif ($lowerCaseType == 'mssql') {
            //@todo ...
        } else {
            //@todo ...
        }

        //设置PDO错误模式为"抛出异常"
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

}
