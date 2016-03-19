<?php

namespace LumengPHP\Db\ConnectionGroup;

use LumengPHP\Db\Connection;

/**
 * 简单数据库连接组，针对只有一个数据库、读写全在一个数据库上的情况。
 *
 * @author Lumeng <zhengb302@163.com>
 */
class SimpleConnectionGroup extends ConnectionGroupBase {

    /**
     * @var Connection 
     */
    private $connection;

    /**
     * 
     * @param int $operation
     * @return Connection
     */
    public function selectConnection($operation) {
        return $this->getConnection();
    }

    /**
     * 
     * @return Connection
     */
    private function getConnection() {
        if (!is_null($this->connection)) {
            return $this->connection;
        }

        $type = $this->groupConfig['type'];
        $config = array(
            'host' => $this->groupConfig['host'],
            'port' => $this->groupConfig['port'],
            'database' => $this->groupConfig['database'],
            'username' => $this->groupConfig['username'],
            'password' => $this->groupConfig['password'],
        );
        $this->connection = new Connection($type, $config);
        return $this->connection;
    }

    public function beginTransaction() {
        return $this->getConnection()->beginTransaction();
    }

    public function commit() {
        return $this->getConnection()->commit();
    }

    public function rollback() {
        return $this->getConnection()->rollBack();
    }

}
