<?php

namespace LumengPHP\Db;

use LumengPHP\Db\Connection\ConnectionInterface;
use LumengPHP\Db\Statement\StatementContext;
use LumengPHP\Db\Statement\SelectStatement;
use LumengPHP\Db\Statement\InsertStatement;
use LumengPHP\Db\Statement\InsertAllStatement;
use LumengPHP\Db\Statement\UpdateStatement;
use LumengPHP\Db\Statement\DeleteStatement;
use LumengPHP\Db\Condition\ConditionInterface;
use LumengPHP\Db\Condition\ArrayCondition;
use LumengPHP\Db\Join\Join;
use LumengPHP\Db\Misc\TableNameHelper;
use LumengPHP\Db\Exception\InvalidSQLConditionException;
use LumengPHP\Db\Exception\SqlException;

/**
 * Model类，子类必须继承才能使用
 *
 * @author zhengluming <908235332@qq.com>
 */
abstract class Model {

    /**
     * @var string 所属的数据库连接名称，可被子类覆盖，用于实例化model时选择数据库连接
     */
    protected $connectionName;

    /**
     * @var ConnectionInterface 所属的数据库连接
     */
    private $connection;

    /**
     * @var StatementContext SQL语句环境
     */
    private $statementContext;

    /**
     * @var string 真实表名(包含表前缀，如果有)，可被子类覆盖，用于自定义表名称
     */
    protected $tableName;

    /**
     * @var ConditionInterface 条件
     */
    private $condition;

    /**
     * 创建一个<b>Model</b>实例
     */
    public function __construct() {
        $this->connection = ConnectionManager::getInstance()->getConnection($this->connectionName);

        //如果子类提供了真实表名，则使用子类提供的表名；如果未提供，则解析Model类名。
        if (!$this->tableName) {
            //Model类名称转化为表名称，如“UserProfileModel”转化为“bbs_user_profile”
            $modelName = TableNameHelper::basename(get_called_class());
            $abstractTableName = substr($modelName, 0, strlen($modelName) - 5);
            $this->tableName = $this->connection->getTablePrefix() . TableNameHelper::camel2id($abstractTableName, '_');
        }

        $this->statementContext = new StatementContext();
        $this->statementContext->setTableName($this->tableName);
    }

    /**
     * 设置别名
     * @param string $alias
     * @return Model
     */
    public function alias($alias) {
        $this->statementContext->setAlias($alias);
        return $this;
    }

    /**
     * 设置查询字段
     * @param string|array $fields 以英文逗号分隔的字段列表或字段数组
     * @return Model
     */
    public function select($fields) {
        $this->statementContext->setFields($fields);
        return $this;
    }

    /**
     * 设置不返回重复的记录
     * @return Model
     */
    public function distinct() {
        $this->statementContext->distinct();
        return $this;
    }

    /**
     * 内连接一个表
     * @param string $table 要连接的表的抽象表名，如"UserProfile"
     * @param string $alias 要连接的表的别名，如"u"。可以为空
     * @param string $on 连接条件，注意不要带上"ON"关键字
     * @return Model
     */
    public function join($table, $alias, $on) {
        $trueTableName = $this->connection->getTablePrefix()
                . TableNameHelper::camel2id($table, '_');

        $join = new Join($trueTableName, $alias, $on);

        $this->statementContext->getJoinClause()->addJoin($join);
        return $this;
    }

    /**
     * 左外连接一个表
     * @param string $table 要连接的表的抽象表名，如"UserProfile"
     * @param string $alias 要连接的表的别名，如"u"。可以为空
     * @param string $on 连接条件，注意不要带上"ON"关键字
     * @return Model
     */
    public function leftJoin($table, $alias, $on) {
        $trueTableName = $this->connection->getTablePrefix()
                . TableNameHelper::camel2id($table, '_');

        $join = new Join($trueTableName, $alias, $on);
        $join->setType(Join::LEFT_JOIN);

        $this->statementContext->getJoinClause()->addJoin($join);
        return $this;
    }

    /**
     * 右外连接一个表
     * @param string $table 要连接的表的抽象表名，如"UserProfile"
     * @param string $alias 要连接的表的别名，如"u"。可以为空
     * @param string $on 连接条件，注意不要带上"ON"关键字
     * @return Model
     */
    public function rightJoin($table, $alias, $on) {
        $trueTableName = $this->connection->getTablePrefix()
                . TableNameHelper::camel2id($table, '_');

        $join = new Join($trueTableName, $alias, $on);
        $join->setType(Join::RIGHT_JOIN);

        $this->statementContext->getJoinClause()->addJoin($join);
        return $this;
    }

    /**
     * 设置查询条件
     * @param array|ConditionInterface $condition
     * @return Model
     */
    public function where($condition) {
        if (is_array($condition)) {
            $this->condition = new ArrayCondition($condition);
        } elseif ($condition instanceof ConditionInterface) {
            $this->condition = $condition;
        } else {
            throw new InvalidSQLConditionException('无效的SQL条件');
        }

        $this->condition->setStatementContext($this->statementContext);

        return $this;
    }

    /**
     * 设置"GROUP BY"子句
     * @param string $groupByClause
     * @return Model
     */
    public function groupBy($groupByClause) {
        $this->statementContext->setGroupBy($groupByClause);
        return $this;
    }

    /**
     * 设置"HAVING"子句
     * @param string $havingClause
     * @return Model
     */
    public function having($havingClause) {
        $this->statementContext->setHaving($havingClause);
        return $this;
    }

    /**
     * 设置"ORDER BY"子句
     * @param string|array $orderByClause
     * @return Model
     */
    public function orderBy($orderByClause) {
        $this->statementContext->setOrderBy($orderByClause);
        return $this;
    }

    /**
     * 设置分页<br />
     * 注意：设置分页和设置limit子句会导致互相覆盖
     * @param int $pageNum 页号(从1开始)
     * @param int $pageSize 页大小
     * @return Model
     */
    public function paging($pageNum, $pageSize) {
        $offset = ($pageNum - 1) * $pageSize;
        $this->statementContext->setLimit("{$offset}, {$pageSize}");
        return $this;
    }

    /**
     * 设置limit子句<br />
     * 注意：设置分页和设置limit子句会导致互相覆盖
     * @param int|string $limit limit子句。注意，不包括LIMIT关键字<br />
     * 示例：<br />
     * //结果SQL：... LIMIT 5 ...
     * $model->limit(5);
     * 
     * //结果SQL：... LIMIT 40,10 ...
     * $model->limit('40,10');
     * 
     * @return Model
     */
    public function limit($limit) {
        $this->statementContext->setLimit($limit);
        return $this;
    }

    /**
     * 查询一条记录
     * @return array|null|false 成功则返回一个关联数组；未找到数据返回null，SQL执行发生错误则返回false
     */
    public function findOne() {
        $this->statementContext->setLimit(1);

        $statement = new SelectStatement();
        $statement->setStatementContext($this->statementContext);
        $statement->setCondition($this->condition);
        $sql = $statement->parse();

        $row = $this->connection->query($sql, $this->statementContext->getParameters());

        $this->clear();

        return $row;
    }

    /**
     * 查询多条记录
     * @return array|null|false 成功则返回一个结果数组；未找到数据返回null，SQL执行发生错误则返回false
     */
    public function findAll() {
        $statement = new SelectStatement();
        $statement->setStatementContext($this->statementContext);
        $statement->setCondition($this->condition);
        $sql = $statement->parse();

        $rows = $this->connection->queryAll($sql, $this->statementContext->getParameters());

        $this->clear();

        return $rows;
    }

    /**
     * 查找单个值(某条记录的某个字段的值)
     * @param string $field 单个字段名称，注意不要使用字段别名
     * @return mixed|null|false 成功则返回相应的值；未找到数据返回null，SQL执行发生错误则返回false
     */
    public function findValue($field) {
        $row = $this->select($field)->findOne();

        //false or null
        if (!$row) {
            return $row;
        }

        return $row[$field];
    }

    /**
     * 查找一个列的值
     * @param string $field 单个字段名称，注意不要使用字段别名
     * @return array|null|false 成功则返回此列的值的数组；未找到数据返回null，SQL执行发生错误则返回false
     */
    public function findColumn($field) {
        $rows = $this->select($field)->findAll();

        //false or null
        if (!$rows) {
            return $rows;
        }

        $columnValues = [];
        foreach ($rows as $row) {
            $columnValues[] = $row[$field];
        }
        return $columnValues;
    }

    /**
     * 执行count聚簇函数
     * @param string $field
     * @return int|false 如果成功则返回相应的值；SQL执行发生错误则返回false
     */
    public function count($field = '*') {
        $this->statementContext->setFields("COUNT({$field}) AS __COUNT__");
        $row = $this->findOne();

        //SQL执行发生错误
        if ($row === false) {
            return false;
        }

        return $row['__COUNT__'];
    }

    /**
     * 执行max聚簇函数
     * @param string $field
     * @return mixed|null|false 如果成功则返回相应的值；不存在相应的记录返回null；
     * SQL执行发生错误则返回false
     */
    public function max($field) {
        $this->statementContext->setFields("MAX({$field}) AS __MAX__");
        $row = $this->findOne();

        //SQL执行发生错误
        if ($row === false) {
            return false;
        }

        return $row['__MAX__'];
    }

    /**
     * 执行min聚簇函数
     * @param string $field
     * @return mixed|null|false 如果成功则返回相应的值；不存在相应的记录返回null；
     * SQL执行发生错误则返回false
     */
    public function min($field) {
        $this->statementContext->setFields("MIN({$field}) AS __MIN__");
        $row = $this->findOne();

        //SQL执行发生错误
        if ($row === false) {
            return false;
        }

        return $row['__MIN__'];
    }

    /**
     * 执行avg聚簇函数
     * @param string $field
     * @return mixed|null|false 如果成功则返回相应的值；不存在相应的记录返回null；
     * SQL执行发生错误则返回false
     */
    public function avg($field) {
        $this->statementContext->setFields("AVG({$field}) AS __AVG__");
        $row = $this->findOne();

        //SQL执行发生错误
        if ($row === false) {
            return false;
        }

        return $row['__AVG__'];
    }

    /**
     * 执行sum聚簇函数
     * @param string $field
     * @return mixed|null|false 如果成功则返回相应的值；不存在相应的记录返回null；
     * SQL执行发生错误则返回false
     */
    public function sum($field) {
        $this->statementContext->setFields("SUM({$field}) AS __SUM__");
        $row = $this->findOne();

        //SQL执行发生错误
        if ($row === false) {
            return false;
        }

        return $row['__SUM__'];
    }

    /**
     * 插入一条数据
     * @param array $data 要插入的数据，关联数组
     * @return string|false 插入成功则返回新插入记录的id，SQL执行发生错误返回false
     */
    public function insert(array $data) {
        $statement = new InsertStatement($data);
        $statement->setStatementContext($this->statementContext);
        $sql = $statement->parse();

        $result = $this->connection->execute($sql, $this->statementContext->getParameters());

        $this->clear();

        if ($result === false) {
            return false;
        }

        return $this->connection->lastInsertId();
    }

    /**
     * 批量插入数据
     * @param array $dataArr 要批量插入的数据，下标数组
     * @return string|false 插入成功则返回新插入的记录数，SQL执行发生错误返回false
     */
    public function insertAll(array $dataArr) {
        $statement = new InsertAllStatement($dataArr);
        $statement->setStatementContext($this->statementContext);
        $sql = $statement->parse();

        $rowCount = $this->connection->execute($sql, $this->statementContext->getParameters());

        $this->clear();

        return $rowCount;
    }

    /**
     * 更新数据
     * @param array $data 要更新的数据，关联数组
     * @return int|false 成功则返回受影响的行数；如果SQL执行发生错误，返回false。
     * 注意返回<b>0</b>和返回<b>false</b>的区别。
     */
    public function update(array $data) {
        $statement = new UpdateStatement($data);
        $statement->setStatementContext($this->statementContext);
        $statement->setCondition($this->condition);
        $sql = $statement->parse();

        $rowCount = $this->connection->execute($sql, $this->statementContext->getParameters());

        $this->clear();

        return $rowCount;
    }

    /**
     * 给一个字段增加指定的值
     * @param string $field 字段名称
     * @param int|float $amount 指定的值
     * @return int|false 成功则返回受影响的行数；如果SQL执行发生错误，返回false。
     * 注意返回<b>0</b>和返回<b>false</b>的区别。
     */
    public function inc($field, $amount = 1) {
        $this->checkIncOrDecAmount($amount);

        $data = [$field => ['exp', "{$field} + {$amount}"]];
        return $this->update($data);
    }

    /**
     * 把一个字段减去指定的值
     * @param string $field 字段名称
     * @param int|float $amount 指定的值
     * @return int|false 成功则返回受影响的行数；如果SQL执行发生错误，返回false。
     * 注意返回<b>0</b>和返回<b>false</b>的区别。
     */
    public function dec($field, $amount = 1) {
        $this->checkIncOrDecAmount($amount);

        $data = [$field => ['exp', "{$field} - {$amount}"]];
        return $this->update($data);
    }

    /**
     * 校验inc或dec方法的$amount参数
     * @param mixed $amount
     * @throws SqlException
     */
    private function checkIncOrDecAmount($amount) {
        if (!is_int($amount) && !is_float($amount)) {
            throw new SqlException('inc或dec方法的$amount参数值必须是整数或浮点数');
        }
    }

    /**
     * 删除数据
     * @return int|false 如果成功，则返回受影响的行数；如果SQL执行发生错误，返回false。
     * 注意返回<b>0</b>和返回<b>false</b>的区别。
     */
    public function delete() {
        $statement = new DeleteStatement();
        $statement->setStatementContext($this->statementContext);
        $statement->setCondition($this->condition);
        $sql = $statement->parse();

        $rowCount = $this->connection->execute($sql, $this->statementContext->getParameters());

        $this->clear();

        return $rowCount;
    }

    private function clear() {
        $this->statementContext->clear();
        $this->condition = null;
    }

    /**
     * 返回所属的数据库连接
     * @return ConnectionInterface
     */
    public function getConnection() {
        return $this->connection;
    }

}
