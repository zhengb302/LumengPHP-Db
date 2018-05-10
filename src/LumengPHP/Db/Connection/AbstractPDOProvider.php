<?php

namespace LumengPHP\Db\Connection;

use LumengPHP\Db\Exception\SqlException;
use PDO;

/**
 * 
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
abstract class AbstractPDOProvider implements PDOProviderInterface {

    /**
     * @var array 连接配置
     */
    protected $config;

    public function __construct($config) {
        $this->config = $config;
    }

    protected function makeDsn($host, $port, $dbName) {
        $type = $this->config['type'];

        $dsn = '';
        switch ($type) {
            case 'mysql':
                $dsn = "mysql:host={$host};port={$port};dbname={$dbName}";
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

}
