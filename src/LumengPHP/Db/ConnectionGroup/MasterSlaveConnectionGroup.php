<?php

namespace LumengPHP\Db\ConnectionGroup;

use LumengPHP\Db\Connection;

/**
 * 主从数据库连接组
 *
 * @author Lumeng <zhengb302@163.com>
 */
class MasterSlaveConnectionGroup extends ConnectionGroupBase {

    /**
     * @var array 脏的数据库表数组
     */
    private $dirtyTables = array();

    /**
     * @var Connection 主数据库连接
     */
    private $masterConnection;

    /**
     * @var Connection 从数据库连接
     */
    private $slaveConnection;

    /**
     * @var bool 是否在事务里
     */
    private $inTransaction = false;

    public function selectConnection($operation, $tableName) {
        $conn = null;
        switch ($operation) {
            case Connection::OP_READ:
                //如果要读取的表已经被更新过，则从Master读取；或者，
                //当前操作在某个事务里，则也从Master读取
                if (in_array($tableName, $this->dirtyTables) ||
                        $this->inTransaction) {
                    $conn = $this->getMasterConnection();
                    break;
                }

                $conn = $this->selectSlaveConnection();
                break;
            case Connection::OP_WRITE:
                $conn = $this->getMasterConnection();

                //要更新的表加入到dirtyTables中
                if (!in_array($tableName, $this->dirtyTables)) {
                    $this->dirtyTables[] = $tableName;
                }

                break;
            default:
                trigger_error('不受支持的数据库操作！', E_USER_ERROR);
        }

        return $conn;
    }

    /**
     * 获取Master连接
     * @return Connection
     */
    private function getMasterConnection() {
        if (!is_null($this->masterConnection)) {
            return $this->masterConnection;
        }

        $type = $this->groupConfig['type'];
        $config = $this->groupConfig['servers'][0];
        $this->masterConnection = new Connection($type, $config);
        return $this->masterConnection;
    }

    private function selectSlaveConnection() {
        if (!is_null($this->slaveConnection)) {
            return $this->slaveConnection;
        }

        //随机选取"从服务器"
        $slaveIndex = mt_rand(1, count($this->groupConfig['servers']) - 1);

        $type = $this->groupConfig['type'];
        $config = $this->groupConfig['servers'][$slaveIndex];
        $this->slaveConnection = new Connection($type, $config);
        return $this->slaveConnection;
    }

    public function beginTransaction() {
        $this->inTransaction = true;
        return $this->getMasterConnection()->beginTransaction();
    }

    public function commit() {
        $this->inTransaction = false;
        return $this->getMasterConnection()->commit();
    }

    public function rollback() {
        $this->inTransaction = false;
        return $this->getMasterConnection()->rollBack();
    }

}
