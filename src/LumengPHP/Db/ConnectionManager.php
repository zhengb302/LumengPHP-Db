<?php

namespace LumengPHP\Db;

use LumengPHP\Db\Connection\Connection;
use Psr\Log\LoggerInterface;

/**
 * 连接管理器
 *
 * @author zhengluming <luming.zheng@baozun.cn>
 */
class ConnectionManager {

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

    /**
     * @var LoggerInterface 可选的日志组件
     */
    private $logger;

    public function __construct($dbConfigs, LoggerInterface $logger = null) {
        $this->dbConfigs = $dbConfigs;

        //选取第一个连接名称作为默认连接名称
        $this->defaultConnectionName = array_keys($dbConfigs)[0];

        $this->logger = $logger;
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
            trigger_error("undefined database connection \"{$name}\".", E_USER_ERROR);
        }

        $connConfig = $this->dbConfigs[$name];
        $class = $connConfig['class'];
        unset($connConfig['class']);

        $conn = new $class($name, $connConfig, $this->logger);
        $this->connectionMap[$name] = $conn;

        return $conn;
    }

}
