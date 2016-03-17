<?php

namespace LumengPHP\Db;

use LumengPHP\Db\ConnectionGroup\ConnectionGroup;

/**
 * 事务管理器(事务方面的Facade)<br />
 * Usage:
 *   //general use case
 *   TransactionManager::beginTransaction();
 *   //if success
 *   TransactionManager::commit();
 *   //else if failed
 *   TransactionManager::rollback();
 * 
 *   //when need transaction on another connection group
 *   //first, switch
 *   TransactionManager::switchGroup('new group name');
 *   //then
 *   TransactionManager::beginTransaction();
 *   //if success
 *   TransactionManager::commit();
 *   //else if failed
 *   TransactionManager::rollback();
 *   //when transaction finished, no matter commit or rollback,
 *   //it will switch back to default connection group.
 *
 * @author Lumeng <zhengb302@163.com>
 */
class TransactionManager {

    private static $groupName;

    /**
     * 切换事务所在的数据库连接组(默认情况下，是在默认连接组中进行事务操作)
     * @param string $newGroupName
     */
    public static function switchGroup($newGroupName) {
        self::$groupName = $newGroupName;
    }

    /**
     * 开始事务
     * @return bool TRUE on success or FALSE on failure.
     */
    public static function beginTransaction() {
        return self::getConnectionGroup()->beginTransaction();
    }

    /**
     * 提交事务
     * @return bool TRUE on success or FALSE on failure.
     */
    public static function commit() {
        $result = self::getConnectionGroup()->commit();

        self::$groupName = null;

        return $result;
    }

    /**
     * 回滚事务
     * @return bool TRUE on success or FALSE on failure.
     */
    public static function rollback() {
        $result = self::getConnectionGroup()->rollback();

        self::$groupName = null;

        return $result;
    }

    /**
     * 
     * @return ConnectionGroup
     */
    private static function getConnectionGroup() {
        return ConnectionManager::getInstance()
                        ->getConnectionGroup(self::$groupName);
    }

}
