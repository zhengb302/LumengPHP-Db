<?php

namespace LumengPHP\Db;

use Closure;
use LumengPHP\Db\Connection\Connection;
use LumengPHP\Db\Connection\ConnectionInterface;
use LumengPHP\Db\Connection\PDOFactoryInterface;
use LumengPHP\Db\Exception\SqlException;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use ReflectionFunction;

/**
 * 连接管理器
 *
 * @author zhengluming <908235332@qq.com>
 */
final class ConnectionManager {

    /**
     * @var array 数据库配置，格式：connectionName => connectionConfig/Closure
     */
    private $connectionConfigs;

    /**
     * @var string 默认的数据库连接名称
     */
    private $defaultConnectionName;

    /**
     * @var array 数据库连接map，格式：name => connectionInstance
     */
    private $connectionMap = [];

    /**
     * @var LoggerInterface 可选的日志组件
     */
    private $logger;

    private function __construct($connectionConfigs, LoggerInterface $logger = null) {
        $this->connectionConfigs = $connectionConfigs;

        //选取第一个连接名称作为默认连接名称
        $this->defaultConnectionName = array_keys($connectionConfigs)[0];

        $this->logger = $logger ?: new NullLogger();
    }

    /**
     * 返回默认的数据库连接对象
     * 
     * @return ConnectionInterface
     */
    public function getDefaultConnection() {
        return $this->getConnection();
    }

    /**
     * 根据数据库连接名称返回数据库连接对象
     * 
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
            throw new SqlException("未定义的数据库连接，连接名称：{$name}");
        }

        $connConfig = $this->connectionConfigs[$name];
        $conn = $this->buildConnection($connConfig);

        $this->connectionMap[$name] = $conn;
        return $conn;
    }

    /**
     * 根据连接配置构造连接实例
     * 
     * @param array|Closure $connConfig 连接配置/回调函数
     * @return ConnectionInterface 
     */
    private function buildConnection($connConfig) {
        //基于数组的连接配置
        if (is_array($connConfig)) {
            $pdoFactoryClass = $connConfig['pdoFactory'];
            unset($connConfig['pdoFactory']);

            /* @var $conn PDOFactoryInterface */
            $pdoFactory = new $pdoFactoryClass($connConfig);

            $conn = new Connection($pdoFactory, $this->logger);
            $conn->setTablePrefix($connConfig['tablePrefix']);
        }
        //回调函数
        //回调函数可以接收当前 ConnectionManager 实例作为参数，当然，这个参数是可选的
        elseif ($connConfig instanceof Closure) {
            $callback = $connConfig;
            $refFunc = new ReflectionFunction($callback);
            $conn = $refFunc->getNumberOfParameters() == 1 ? $callback($this) : $callback();
        } else {
            throw new SqlException('连接配置类型错误');
        }

        return $conn;
    }

    /**
     * 返回日志组件
     * 
     * @return LoggerInterface
     */
    public function getLogger() {
        return $this->logger;
    }

    /**
     * @var ConnectionManager 
     */
    private static $instance;

    /**
     * 创建并返回<b>ConnectionManager</b>实例
     * 
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
     * 
     * @return ConnectionManager
     */
    public static function getInstance() {
        return self::$instance;
    }

}
