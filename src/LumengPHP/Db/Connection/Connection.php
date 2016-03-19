<?php

namespace LumengPHP\Db\Connection;

/**
 * 数据库连接接口
 * @author Lumeng <zhengb302@163.com>
 */
interface Connection {

    /**
     * 返回连接名称
     * @return string 连接名称
     */
    public function getName();

    /**
     * 返回表前缀
     * @return string
     */
    public function getTablePrefix();

    /**
     * 执行一条SELECT查询语句，并返回一条记录（如果有）
     * @param string $sql 带占位符的SQL语句
     * @param array $parameters 绑定参数。参数个数必须和SQL语句中的占位符数量一样多
     * @return array|null|false 成功则返回相应的数据，没有符合条件的记录则返回null，
     * SQL执行错误则返回false。注意：这里所谓的"执行成功"只是SQL执行没发生错误，
     * 并不意味着找到了数据或更新了数据。
     */
    public function query($sql, $parameters = null);

    /**
     * 执行一条SELECT查询语句，并返回多条记录（如果有）
     * @param string $sql 带占位符的SQL语句
     * @param array $parameters 绑定参数。参数个数必须和SQL语句中的占位符数量一样多
     * @return array|null|false 成功则返回相应的数据，没有符合条件的记录则返回null，
     * SQL执行错误则返回false。注意：这里所谓的"执行成功"只是SQL执行没发生错误，
     * 并不意味着找到了数据或更新了数据。
     */
    public function queryAll($sql, $parameters = null);

    /**
     * 执行一条INSERT、UPDATE或DELETE语句，并返回相应的执行结果
     * @param string $sql 带占位符的SQL语句
     * @param array $parameters 绑定参数。参数个数必须和SQL语句中的占位符数量一样多
     * @return int|false 成功则返回受影响的行数，SQL执行错误则返回false。
     * 注意：这里所谓的"执行成功"只是SQL执行没发生错误，并不意味着找到了数据或更新了数据。
     */
    public function execute($sql, array $parameters = null);

    /**
     * 返回最近一次执行INSERT语句所生成的新记录的ID
     * @param string $name 应该返回ID的那个序列对象的名称。
     * @return int 
     */
    public function lastInsertId($name = null);

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
