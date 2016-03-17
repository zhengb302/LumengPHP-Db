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
     * 返回连接管理器实例
     * @return ConnectionManager
     */
    public static function getConnectionManager() {
        if (is_null(self::$connectionManager)) {
            self::$connectionManager = new self();
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

    private function __construct() {
        
    }

    public function __clone() {
        trigger_error(__CLASS__ . '不能复制呦~', E_USER_ERROR);
    }

    public function loadDbConfigs($dbConfigs) {
        foreach ($dbConfigs as $groupName => $groupConfig) {
            $connGroup = new $groupConfig['class']($groupName, $groupConfig);
            $this->connectionGroupMap[$groupName] = $connGroup;

            //这会使第一个数据库组成为默认组
            if (is_null($this->defaultConnectionGroup)) {
                $this->defaultConnectionGroup = $connGroup;
            }
        }
    }

    /**
     * 
     * @param string|null $groupName 组名，为null则返回默认组
     * @return ConnectionGroup
     */
    public function getConnectionGroup($groupName) {
        if (is_null($groupName)) {
            return $this->defaultConnectionGroup;
        }

        if (isset($this->connectionGroupMap[$groupName])) {
            return $this->connectionGroupMap[$groupName];
        }

        trigger_error("未定义的数据库连接组，组名：{$groupName}", E_USER_ERROR);
    }

    /**
     * （在默认连接组中）开始事务
     * @return bool TRUE on success or FALSE on failure.
     */
    public function beginTransaction() {
        return $this->defaultConnectionGroup->beginTransaction();
    }

    /**
     * （在默认连接组中）提交事务
     * @return bool TRUE on success or FALSE on failure.
     */
    public function commit() {
        return $this->defaultConnectionGroup->commit();
    }

    /**
     * （在默认连接组中）回滚事务
     * @return bool TRUE on success or FALSE on failure.
     */
    public function rollback() {
        return $this->defaultConnectionGroup->rollback();
    }

}
