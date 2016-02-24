<?php

namespace LumengPHP\Db;

/**
 * 数据库连接组
 * @author Lumeng <zhengb302@163.com>
 */
interface ConnectionGroup {

    /**
     * 返回连接组名称
     * @return string 连接组名称
     */
    public function getGroupName();

    /**
     * 返回表前缀
     * @return string
     */
    public function getTablePrefix();

    /**
     * 选择数据库连接
     * @param int $operation 操作：OP_READ、OP_WRITE
     * @param string $tableName 要操作的表名
     * @return Connection
     */
    public function selectConnection($operation, $tableName);

    /**
     * 开始事务
     * @return bool TRUE on success or FALSE on failure.
     */
    public function beginTransaction();

    /**
     * 提交事务
     * @return bool TRUE on success or FALSE on failure.
     */
    public function commit();

    /**
     * 回滚事务
     * @return bool TRUE on success or FALSE on failure.
     */
    public function rollback();
}
