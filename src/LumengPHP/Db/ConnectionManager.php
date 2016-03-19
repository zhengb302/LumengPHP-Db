<?php

namespace LumengPHP\Db;

use LumengPHP\Db\Connection\Connection;

/**
 * 连接管理器
 *
 * @author zhengluming <luming.zheng@baozun.cn>
 */
class ConnectionManager {

    /**
     * @var ConnectionManager 连接管理器唯一实例
     */
    private static $connectionManager;

    /**
     * 创建并返回连接管理器实例
     * @param array $dbConfigs 数据库配置
     * @return ConnectionManager
     */
    public static function create($dbConfigs) {
        if (!is_null(self::$connectionManager)) {
            //@todo trigger error
        }

        self::$connectionManager = new self($dbConfigs);

        return self::$connectionManager;
    }

    /**
     * 返回连接管理器实例
     * @return ConnectionManager
     */
    public static function getInstance() {
        return self::$connectionManager;
    }

    /**
     * @var array 数据库配置
     */
    private $dbConfigs;

    /**
     * @var string 默认的数据库连接名称
     */
    private $defaultConnectionName;

    /**
     * @var array 数据库连接map，格式：name => connectionInstance
     */
    private $connectionMap = array();

    private function __construct($dbConfigs) {
        $this->dbConfigs = $dbConfigs;

        foreach ($dbConfigs as $name => $config) {
            //选取第一个连接名称作为默认连接名称之后，退出循环
            $this->defaultConnectionName = $name;
            break;
        }
    }

    public function __clone() {
        trigger_error(__CLASS__ . '不能复制呦~', E_USER_ERROR);
    }

    /**
     * 根据数据库连接名称返回数据库连接对象
     * @param string|null $name 连接名称，为null则返回默认连接
     * @return Connection
     */
    public function getConnection($name = null) {
        if (is_null($name)) {
            $name = $this->defaultConnectionName;
        }

        if (isset($this->connectionMap[$name])) {
            return $this->connectionMap[$name];
        }

        if (!isset($this->dbConfigs[$name])) {
            trigger_error("未定义的数据库连接，连接名称：{$name}", E_USER_ERROR);
        }

        $connConfig = $this->dbConfigs[$name];
        $class = $connConfig['class'];
        unset($connConfig['class']);

        $conn = new $class($name, $connConfig);
        $this->connectionMap[$name] = $conn;

        return $conn;
    }

}
