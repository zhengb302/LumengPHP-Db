<?php

namespace LumengPHP\Db\Connection;

use Psr\Log\LoggerInterface;

/**
 * 数据库连接接口
 * @author Lumeng <zhengb302@163.com>
 */
interface ConnectionInterface {

    /**
     * 设置数据库连接名称，用以注入数据库连接名称
     * @param string $name
     */
    public function setName($name);

    /**
     * 设置数据库连接配置，用以注入数据库连接配置
     * @param array $config
     */
    public function setConfig($config);

    /**
     * 设置日志组件，用以注入日志组件
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger);

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
     * 执行一条<b>SELECT</b>查询语句，并返回第一条记录（如果有）
     * @param string $sql 带占位符的SQL语句
     * @param array $parameters 绑定参数。参数个数必须和SQL语句中的占位符数量一样多
     * @return array|null|false 成功则返回相应的数据，没有符合条件的记录则返回null，
     * SQL执行错误则返回false。注意：这里所谓的"执行成功"只是SQL执行没发生错误，
     * 并不意味着找到了数据或更新了数据。
     */
    public function query($sql, $parameters = null);

    /**
     * 执行一条<b>SELECT</b>查询语句，并返回多条记录（如果有）
     * @param string $sql 带占位符的SQL语句
     * @param array $parameters 绑定参数。参数个数必须和SQL语句中的占位符数量一样多
     * @return array|null|false 成功则返回相应的数据，没有符合条件的记录则返回null，
     * SQL执行错误则返回false。注意：这里所谓的"执行成功"只是SQL执行没发生错误，
     * 并不意味着找到了数据或更新了数据。
     */
    public function queryAll($sql, $parameters = null);

    /**
     * 执行一条<b>INSERT</b>、<b>UPDATE</b>或<b>DELETE</b>语句，并返回相应的执行结果
     * @param string $sql 带占位符的SQL语句
     * @param array $parameters 绑定参数。参数个数必须和SQL语句中的占位符数量一样多
     * @return int|false 成功则返回受影响的行数，SQL执行错误则返回false。
     * 注意：这里所谓的"执行成功"只是SQL执行没发生错误，并不意味着找到了数据或更新了数据。
     */
    public function execute($sql, array $parameters = null);

    /**
     * 返回最近一次执行<b>INSERT</b>语句所生成的新记录的ID
     * @param string $name 应该返回ID的那个序列对象的名称。
     * @return int 
     */
    public function lastInsertId($name = null);

    /**
     * 开始事务<br />
     * 对于配置了主从模式的架构，执行此方法会导致该连接上的所有<b>读</b>操作都在master上进行，直到提交或回滚事务。
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

    /**
     * 禁用“从服务器”。执行此方法会导致该连接上的所有<b>读</b>操作都在master上进行，
     * 直到调用了enableSlaves()方法为止。该方法一般和enableSlaves()方法一起使用。<br />
     * 注意：这只对配置了主从模式的架构起作用，对于不支持主从模式的架构，则在此方法里什么都不做
     */
    public function disableSlaves();

    /**
     * 启用“从服务器”。执行此方法会恢复之前的读写分离模式，
     * 该方法一般和disableSlaves()方法一起使用。<br />
     * 注意：这只对配置了主从模式的架构起作用，对于不支持主从模式的架构，则在此方法里什么都不做
     */
    public function enableSlaves();

    /**
     * 返回最后一条执行的SQL(用于错误排查及调试)
     * @return string 
     */
    public function getLastSql();
}
