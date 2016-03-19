<?php

namespace LumengPHP\Db\Connection;

use PDO;

/**
 * 简单数据库连接，针对只有一个数据库、读写全在一个数据库上的情况。
 *
 * @author Lumeng <zhengb302@163.com>
 */
class SimpleConnection extends ConnectionBase {

    /**
     * @var PDO 
     */
    private $pdo;

    public function execute($sql, array $parameters = null) {
        
    }

    public function lastInsertId($name = null) {
        
    }

    public function query($sql, $parameters = null) {
        
    }

    public function queryAll($sql, $parameters = null) {
        
    }

    public function beginTransaction() {
        return $this->getPdo()->beginTransaction();
    }

    public function commit() {
        return $this->getPdo()->commit();
    }

    public function rollback() {
        return $this->getPdo()->rollBack();
    }

    /**
     * 
     * @return PDO
     */
    private function getPdo() {
        if (!is_null($this->pdo)) {
            return $this->pdo;
        }

        $dsn = $this->config['dsn'];
        $username = $this->config['username'];
        $password = $this->config['password'];
        $this->pdo = $this->makePdo($dsn, $username, $password);

        return $this->pdo;
    }

}
