<?php

namespace LumengPHP\Db\Connection;

use PDO;

/**
 * 简单数据库连接，针对只有一个数据库、读写全在一个数据库上的情况。
 *
 * @author Lumeng <zhengb302@163.com>
 */
class SimpleConnection extends AbstractConnection {

    /**
     * @var PDO 
     */
    private $pdo;

    /**
     * {@inheritdoc}
     */
    public function query($sql, $parameters = null) {
        return $this->doQuery($this->getPdo(), $sql, $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function queryAll($sql, $parameters = null) {
        return $this->doQueryAll($this->getPdo(), $sql, $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function execute($sql, array $parameters = null) {
        return $this->doExecute($this->getPdo(), $sql, $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function lastInsertId($name = null) {
        return $this->getPdo()->lastInsertId($name);
    }

    /**
     * {@inheritdoc}
     */
    public function beginTransaction() {
        return $this->getPdo()->beginTransaction();
    }

    /**
     * {@inheritdoc}
     */
    public function commit() {
        return $this->getPdo()->commit();
    }

    /**
     * {@inheritdoc}
     */
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

    public function disableSlaves() {
        //do nothing
    }

    public function enableSlaves() {
        //do nothing
    }

}
