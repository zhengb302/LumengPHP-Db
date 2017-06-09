<?php

namespace LumengPHP\Db;

use Psr\Log\LoggerInterface;
use LumengPHP\Db\Connection\ConnectionInterface;
use LumengPHP\Db\Exception\SqlException;

/**
 * 连接管理器
 *
 * @author zhengluming <908235332@qq.com>
 */
final class ConnectionManager {

    /**
     * @var array 数据库配置，格式：connectionName => connectionConfig
     */
    private $connectionConfigs;

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

    private function __construct($connectionConfigs, LoggerInterface $logger = null) {
        $this->connectionConfigs = $connectionConfigs;

        //选取第一个连接名称作为默认连接名称
        $this->defaultConnectionName = array_keys($connectionConfigs)[0];

        $this->logger = $logger;
    }

    /**
     * 返回默认的数据库连接对象
     * @return ConnectionInterface
     */
    public function getDefaultConnection() {
        return $this->getConnection();
    }

    /**
     * 根据数据库连接名称返回数据库连接对象
     * @param string|null $name 连接名称，为null则返回默认连接
     * @return ConnectionInterface
     */
    public function getConnection($name = null) {
        if (is_null($name)) {
            $name = $this->defaultConnectionName;
        }

        if (isset($this->connectionMap[$name])) {
            return $this->connectionMap[$name];
        }

        if (!isset($this->connectionConfigs[$name])) {
            trigger_error("undefined database connection \"{$name}\".", E_USER_ERROR);
        }

        $connConfig = $this->connectionConfigs[$name];
        $class = $connConfig['class'];
        unset($connConfig['class']);

        $conn = new $class();
        $conn->setName($name);
        $conn->setConfig($connConfig);
        $conn->setLogger($this->logger);

        $this->connectionMap[$name] = $conn;
        return $conn;
    }

    /**
     * @var ConnectionManager 
     */
    private static $instance;

    /**
     * 创建并返回<b>ConnectionManager</b>实例
     * @param array $connectionConfigs 数据库配置
     * @param LoggerInterface $logger 日志组件
     * @return ConnectionManager
     * @throws SqlException
     */
    public static function create($connectionConfigs, LoggerInterface $logger = null) {
        if (!is_null(self::$instance)) {
            throw new SqlException('ConnectionManager实例已创建，不能重复创建~');
        }

        self::$instance = new ConnectionManager($connectionConfigs, $logger);
        return self::$instance;
    }

    /**
     * 返回<b>ConnectionManager</b>实例
     * @return ConnectionManager
     */
    public static function getInstance() {
        return self::$instance;
    }

}
