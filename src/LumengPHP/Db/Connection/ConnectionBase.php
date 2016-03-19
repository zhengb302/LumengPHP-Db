<?php

namespace LumengPHP\Db\Connection;

use PDO;

/**
 * 数据库连接基类
 *
 * @author Lumeng <zhengb302@163.com>
 */
abstract class ConnectionBase implements Connection {

    /**
     * @var string 连接名称
     */
    protected $name;

    /**
     * @var array 连接配置
     */
    protected $config;

    public function __construct($name, $config) {
        $this->name = $name;
        $this->config = $config;
    }

    public function getName() {
        return $this->name;
    }

    public function getTablePrefix() {
        return $this->config['tablePrefix'];
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

        return $pdo;
    }

}
