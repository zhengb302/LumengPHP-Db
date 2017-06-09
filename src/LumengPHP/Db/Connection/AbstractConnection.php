<?php

namespace LumengPHP\Db\Connection;

use PDO;
use PDOStatement;
use Exception;
use LumengPHP\Db\Exception\SqlException;
use Psr\Log\LoggerInterface;

/**
 * 数据库连接基类
 *
 * @author Lumeng <zhengb302@163.com>
 */
abstract class AbstractConnection implements ConnectionInterface {

    /**
     * @var string 连接名称
     */
    protected $name;

    /**
     * @var array 连接配置
     */
    protected $config;

    /**
     * @var LoggerInterface 日志组件
     */
    protected $logger;

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

    public function setName($name) {
        $this->name = $name;
    }

    public function setConfig($config) {
        $this->config = $config;
    }

    public function setLogger(LoggerInterface $logger) {
        $this->logger = $logger;
    }

    public function getName() {
        return $this->name;
    }

    public function getTablePrefix() {
        return $this->config['tablePrefix'];
    }

    public function getLastSql() {
        $search = array_keys($this->lastParameters);

        $replace = array_values($this->lastParameters);
        foreach ($replace as $i => $value) {
            $replace[$i] = $this->lastPdo->quote($value);
        }

        return str_replace($search, $replace, $this->lastSql);
    }

    protected function makeDsn($host, $port, $dbName) {
        $type = $this->config['type'];

        $dsn = '';
        switch ($type) {
            case 'mysql':
                $dsn = "mysql:host={$host};port={$port};dbname={$dbName}";
                break;
            case 'pgsql':
                $dsn = "pgsql:host={$host};port={$port};dbname={$dbName}";
                break;
            case 'sqlsrv':
                $dsn = "sqlsrv:Server={$host},{$port};Database={$dbName}";
                break;
            default:
                throw new SqlException("不支持的数据库类型：{$type}");
        }
        return $dsn;
    }

    /**
     * 创建一个PDO对象并返回
     * @param string $dsn
     * @param string $username
     * @param string $password
     * @param array $options
     * @return PDO
     */
    protected function makePdo($dsn, $username, $password, array $options = null) {
        $pdo = new PDO($dsn, $username, $password, $options);

        //设置PDO错误模式为"抛出异常"
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //设置字符集
        $pdo->exec("SET NAMES {$this->config['charset']}");

        return $pdo;
    }

    protected function doQuery(PDO $pdo, $sql, array $parameters = null) {
        $pdoStmt = $this->executeSql($pdo, $sql, $parameters);

        //SQL执行发生错误
        if ($pdoStmt === false) {
            return false;
        }

        $row = $pdoStmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $row : null;
    }

    protected function doQueryAll(PDO $pdo, $sql, $parameters = null) {
        $pdoStmt = $this->executeSql($pdo, $sql, $parameters);

        //SQL执行发生错误
        if ($pdoStmt === false) {
            return false;
        }

        $rows = $pdoStmt->fetchAll(PDO::FETCH_ASSOC);

        return $rows ? $rows : null;
    }

    protected function doExecute(PDO $pdo, $sql, $parameters = null) {
        $pdoStmt = $this->executeSql($pdo, $sql, $parameters);

        //SQL执行发生错误
        if ($pdoStmt === false) {
            return false;
        }

        return $pdoStmt->rowCount();
    }

    /**
     * 预编译并执行一个SQL语句（这是一个更为底层的方法，供其他更高层的方法调用）
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
            if ($this->logger) {
                $encodedParameters = json_encode($parameters);
                $this->logger->error($ex->getMessage() . ". SQL: {$sql}, parameters: {$encodedParameters}.");
            }

            return false;
        }
    }

}
