<?php

namespace LumengPHP\Db\Connection;

use PDO;

/**
 * 主从数据库连接，支持数据库读写分离
 *
 * @author Lumeng <zhengb302@163.com>
 */
class MasterSlaveConnection extends BaseConnection {

    /**
     * @var PDO 主数据库连接
     */
    private $masterPdo;

    /**
     * @var PDO 从数据库连接
     */
    private $slavePdo;

    /**
     * @var bool 是否在事务里
     */
    private $inTransaction = false;

    /**
     * @var bool 是否只使用master服务器(这会导致该连接上的所有读写操作都在master上进行)
     */
    private $onlyUseMaster = false;

    /**
     * {@inheritdoc}
     */
    public function query($sql, $parameters = null) {
        $pdo = $this->inTransaction || $this->onlyUseMaster ?
                $this->getMasterPdo() : $this->selectSlavePdo();

        return $this->doQuery($pdo, $sql, $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function queryAll($sql, $parameters = null) {
        $pdo = $this->inTransaction || $this->onlyUseMaster ?
                $this->getMasterPdo() : $this->selectSlavePdo();

        return $this->doQueryAll($pdo, $sql, $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function execute($sql, array $parameters = null) {
        return $this->doExecute($this->getMasterPdo(), $sql, $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function lastInsertId($name = null) {
        return $this->getMasterPdo()->lastInsertId($name);
    }

    /**
     * {@inheritdoc}
     */
    public function beginTransaction() {
        $this->inTransaction = true;
        return $this->getMasterPdo()->beginTransaction();
    }

    /**
     * {@inheritdoc}
     */
    public function commit() {
        $this->inTransaction = false;
        return $this->getMasterPdo()->commit();
    }

    /**
     * {@inheritdoc}
     */
    public function rollback() {
        $this->inTransaction = false;
        return $this->getMasterPdo()->rollBack();
    }

    /**
     * 获取Master连接
     * @return Connection
     */
    private function getMasterPdo() {
        if (!is_null($this->masterPdo)) {
            return $this->masterPdo;
        }

        //第一个为master服务器
        $config = $this->config['servers'][0];

        $dsn = $config['dsn'];
        $username = $config['username'];
        $password = $config['password'];
        $this->masterPdo = $this->makePdo($dsn, $username, $password);

        return $this->masterPdo;
    }

    private function selectSlavePdo() {
        if (!is_null($this->slavePdo)) {
            return $this->slavePdo;
        }

        //随机选取"从服务器"
        $slaveIndex = mt_rand(1, count($this->config['servers']) - 1);
        $config = $this->config['servers'][$slaveIndex];

        $dsn = $config['dsn'];
        $username = $config['username'];
        $password = $config['password'];
        $this->slavePdo = $this->makePdo($dsn, $username, $password);

        return $this->slavePdo;
    }

    /**
     * {@inheritdoc}
     */
    public function disableSlaves() {
        $this->onlyUseMaster = true;
    }

    /**
     * {@inheritdoc}
     */
    public function enableSlaves() {
        $this->onlyUseMaster = false;
    }

}
