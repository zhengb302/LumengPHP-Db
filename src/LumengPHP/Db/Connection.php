<?php

namespace LumengPHP\Db;

use PDO;
use PDOStatement;
use Exception;

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

    /**
     * 预编译并执行一个SQL语句
     * @param string $sql 带占位符的SQL语句
     * @param array $parameters 预编译参数
     * @return PDOStatement|false SQL执行成功则返回一个PDOStatement对象，
     * SQL执行错误则返回false。注意：这里所谓的"执行成功"只是SQL执行没发生错误，
     * 并不意味着找到了数据或更新了数据。
     */
    public function execute($sql, array $parameters = null) {
        try {
            $pdoStmt = $this->pdo->prepare($sql);
            $pdoStmt->execute($parameters);
            return $pdoStmt;
        } catch (Exception $e) {
            //@todo log error message
            return false;
        }
    }

    /**
     * 返回最新插入的记录id
     * @return string
     */
    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }

}
