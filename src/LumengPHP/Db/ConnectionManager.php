<?php

namespace LumengPHP\Db;

use LumengPHP\Db\ConnectionGroup\ConnectionGroup;

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
        if (is_null(self::$connectionManager)) {
            //@todo trigger error
        }

        return self::$connectionManager;
    }

    /**
     * @var ConnectionGroup 默认的数据库连接组实例
     */
    private $defaultConnectionGroup;

    /**
     * @var array 数据库连接组map，格式：groupName => groupInstance
     */
    private $connectionGroupMap = array();

    private function __construct($dbConfigs) {
        foreach ($dbConfigs as $groupName => $groupConfig) {
            $connGroup = new $groupConfig['class']($groupName, $groupConfig);
            $this->connectionGroupMap[$groupName] = $connGroup;

            //这会使第一个数据库连接组成为默认组
            if (is_null($this->defaultConnectionGroup)) {
                $this->defaultConnectionGroup = $connGroup;
            }
        }
    }

    public function __clone() {
        trigger_error(__CLASS__ . '不能复制呦~', E_USER_ERROR);
    }

    /**
     * 根据数据库连接组名称返回数据库连接组对象
     * @param string|null $groupName 组名，为null则返回默认组
     * @return ConnectionGroup
     */
    public function getConnectionGroup($groupName = null) {
        if (is_null($groupName)) {
            return $this->defaultConnectionGroup;
        }

        if (isset($this->connectionGroupMap[$groupName])) {
            return $this->connectionGroupMap[$groupName];
        }

        trigger_error("未定义的数据库连接组，组名：{$groupName}", E_USER_ERROR);
    }

}
