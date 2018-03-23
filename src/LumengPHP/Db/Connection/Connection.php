<?php

namespace LumengPHP\Db\Connection;

use Exception;
use PDO;
use PDOStatement;
use Psr\Log\LoggerInterface;

/**
 * 数据库连接类
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class Connection implements ConnectionInterface {

    /**
     * @var string 表前缀
     */
    private $tablePrefix = '';

    /**
     * @var PDOFactoryInterface 
     */
    private $pdoFactory;

    /**
     * @var LoggerInterface 日志组件
     */
    private $logger;

    /**
     * @var bool 是否在事务里
     */
    private $inTransaction = false;

    /**
     * @var bool 是否只使用master服务器(这会导致该连接上的所有读写操作都在master上进行)
     */
    private $onlyUseMaster = false;

    /**
     * @var PDO 最后一次执行SQL所使用的PDO实例
     */
    private $lastPdo;

    /**
     * @var string 最后一条执行的SQL
     */
    private $lastSql;

    /**
     * @var array 最后一次执行SQL所使用的预编译参数
     */
    private $lastParameters;

    public function __construct(PDOFactoryInterface $pdoFactory, LoggerInterface $logger) {
        $this->pdoFactory = $pdoFactory;
        $this->logger = $logger;
    }

    public function setTablePrefix($tablePrefix) {
        $this->tablePrefix = $tablePrefix;
    }

    public function getTablePrefix() {
        return $this->tablePrefix;
    }

    /**
     * {@inheritdoc}
     */
    public function query($sql, $parameters = null) {
        $pdo = $this->inTransaction || $this->onlyUseMaster ?
                $this->pdoFactory->getPDO(PDOFactoryInterface::TYPE_MASTER) :
                $this->pdoFactory->getPDO(PDOFactoryInterface::TYPE_SLAVE);

        $pdoStmt = $this->executeSql($pdo, $sql, $parameters);

        //SQL执行发生错误
        if ($pdoStmt === false) {
            return false;
        }

        $row = $pdoStmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $row : null;
    }

    /**
     * {@inheritdoc}
     */
    public function queryAll($sql, $parameters = null) {
        $pdo = $this->inTransaction || $this->onlyUseMaster ?
                $this->pdoFactory->getPDO(PDOFactoryInterface::TYPE_MASTER) :
                $this->pdoFactory->getPDO(PDOFactoryInterface::TYPE_SLAVE);

        $pdoStmt = $this->executeSql($pdo, $sql, $parameters);

        //SQL执行发生错误
        if ($pdoStmt === false) {
            return false;
        }

        $rows = $pdoStmt->fetchAll(PDO::FETCH_ASSOC);

        return $rows ? $rows : null;
    }

    /**
     * {@inheritdoc}
     */
    public function execute($sql, array $parameters = null) {
        $pdo = $this->pdoFactory->getPDO(PDOFactoryInterface::TYPE_MASTER);

        $pdoStmt = $this->executeSql($pdo, $sql, $parameters);

        //SQL执行发生错误
        if ($pdoStmt === false) {
            return false;
        }

        return $pdoStmt->rowCount();
    }

    /**
     * 预编译并执行一个SQL语句（这是一个更为底层的方法，供其他更高层的方法调用）
     * 
     * @param PDO $pdo PDO实例
     * @param string $sql 带占位符的SQL语句
     * @param array $parameters 预编译参数
     * @return PDOStatement|false SQL执行成功则返回一个PDOStatement对象，
     * SQL执行错误则返回false。注意：这里所谓的"执行成功"只是SQL执行没发生错误，
     * 并不意味着找到了数据或更新了数据。
     */
    private function executeSql(PDO $pdo, $sql, array $parameters = null) {
        $this->lastPdo = $pdo;
        $this->lastSql = $sql;
        $this->lastParameters = $parameters;

        try {
            $pdoStmt = $pdo->prepare($sql);
            $pdoStmt->execute($parameters);
            return $pdoStmt;
        } catch (Exception $ex) {
            $errMsg = '执行SQL语句出错，错误消息：' . $ex->getMessage() . ', SQL：' . $this->getLastSql();
            $this->logger->error($errMsg);

            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function lastInsertId($name = null) {
        return $this->pdoFactory->getPDO(PDOFactoryInterface::TYPE_MASTER)->lastInsertId($name);
    }

    /**
     * {@inheritdoc}
     */
    public function beginTransaction() {
        $this->inTransaction = true;
        return $this->pdoFactory->getPDO(PDOFactoryInterface::TYPE_MASTER)->beginTransaction();
    }

    /**
     * {@inheritdoc}
     */
    public function commit() {
        $this->inTransaction = false;
        return $this->pdoFactory->getPDO(PDOFactoryInterface::TYPE_MASTER)->commit();
    }

    /**
     * {@inheritdoc}
     */
    public function rollback() {
        $this->inTransaction = false;
        return $this->pdoFactory->getPDO(PDOFactoryInterface::TYPE_MASTER)->rollBack();
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

    public function getLastSql() {
        if (!$this->lastParameters) {
            return $this->lastSql;
        }

        $search = array_keys($this->lastParameters);

        $replace = array_values($this->lastParameters);
        foreach ($replace as $i => $value) {
            $replace[$i] = $this->lastPdo->quote($value);
        }

        return str_replace($search, $replace, $this->lastSql);
    }

}
