<?php

namespace LumengPHP\Db;

use LumengPHP\Db\Connection\ConnectionInterface;
use LumengPHP\Db\Statement\SelectStatement;
use LumengPHP\Db\Statement\InsertStatement;
use LumengPHP\Db\Statement\UpdateStatement;
use LumengPHP\Db\Statement\DeleteStatement;
use LumengPHP\Db\Condition\ConditionInterface;
use LumengPHP\Db\Condition\AndCondition;
use LumengPHP\Db\Join\Join;
use LumengPHP\Db\Misc\TableNameHelper;

/**
 * An abstract database access layer.
 *
 * @author zhengluming <908235332@qq.com>
 */
class DataAccessor {

    /**
     * @var ConnectionInterface 所属的数据库连接
     */
    private $connection;

    /**
     * @var StatementContext SQL语句环境
     */
    private $statementContext;

    /**
     * @var string 真实表名（包含表前缀）
     */
    private $tableName;

    /**
     * @var ConditionInterface 条件
     */
    private $condition;

    /**
     * 
     * @param ConnectionInterface $connection 数据库连接
     * @param string $tableName 抽象表名，即驼峰风格的表名，如"UserProfile"
     */
    public function __construct(ConnectionInterface $connection, $tableName) {
        $this->connection = $connection;
        $this->tableName = $this->connection->getTablePrefix() .
                TableNameHelper::camel2id($tableName, '_');

        $this->statementContext = new StatementContext();
        $this->statementContext->setTableName($this->tableName);
    }

    /**
     * 设置查询字段
     * @param string $fields
     * @return Model
     */
    public function field($fields) {
        $this->statementContext->setFields($fields);
        return $this;
    }

    /**
     * 设置查询条件
     * @param array|ConditionInterface $condition
     * @return Model
     */
    public function where($condition) {
        if (is_array($condition)) {
            $this->condition = new AndCondition($condition);
        } elseif ($condition instanceof ConditionInterface) {
            $this->condition = $condition;
        } else {
            trigger_error('where: invalid argument type.', E_USER_ERROR);
        }

        $this->condition->setStatementContext($this->statementContext);

        return $this;
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
     * 设置"order by"子句
     * @param string $orderByClause
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
     * @param mixed $limit limit子句，注意，不包括LIMIT关键字<br />
     * 示例：<br />
     * //结果SQL：... LIMIT 5 ...
     * $model->limit(5);
     * //结果SQL：... LIMIT 40,10 ...
     * $model->limit('40,10');
     * @return Model
     */
    public function limit($limit) {
        $this->statementContext->setLimit($limit);
        return $this;
    }

    /**
     * 查询一条记录
     * @return array|null|false 成功则返回一个关联数组；未找到数据返回null，
     * SQL执行发生错误则返回false
     */
    public function find() {
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
     * @return array|null|false 成功则返回一个结果数组；未找到数据返回null，
     * SQL执行发生错误则返回false
     */
    public function select() {
        $statement = new SelectStatement();
        $statement->setStatementContext($this->statementContext);
        $statement->setCondition($this->condition);
        $sql = $statement->parse();

        $rows = $this->connection->queryAll($sql, $this->statementContext->getParameters());

        $this->clear();

        return $rows;
    }

    /**
     * 执行count聚簇函数
     * @param string $field
     * @return int|false 如果成功则返回相应的值；SQL执行发生错误则返回false
     */
    public function count($field = '*') {
        $this->statementContext->setFields("COUNT({$field}) AS COUNT");
        $row = $this->find();

        //SQL执行发生错误
        if ($row === false) {
            return false;
        }

        return $row['COUNT'];
    }

    /**
     * 执行max聚簇函数
     * @param string $field
     * @return mixed|null|false 如果成功则返回相应的值；不存在相应的记录返回null；
     * SQL执行发生错误则返回false
     */
    public function max($field) {
        $this->statementContext->setFields("MAX({$field}) AS MAX");
        $row = $this->find();

        //SQL执行发生错误
        if ($row === false) {
            return false;
        }

        return $row['MAX'];
    }

    /**
     * 执行min聚簇函数
     * @param string $field
     * @return mixed|null|false 如果成功则返回相应的值；不存在相应的记录返回null；
     * SQL执行发生错误则返回false
     */
    public function min($field) {
        $this->statementContext->setFields("MIN({$field}) AS MIN");
        $row = $this->find();

        //SQL执行发生错误
        if ($row === false) {
            return false;
        }

        return $row['MIN'];
    }

    /**
     * 执行avg聚簇函数
     * @param string $field
     * @return mixed|null|false 如果成功则返回相应的值；不存在相应的记录返回null；
     * SQL执行发生错误则返回false
     */
    public function avg($field) {
        $this->statementContext->setFields("AVG({$field}) AS AVG");
        $row = $this->find();

        //SQL执行发生错误
        if ($row === false) {
            return false;
        }

        return $row['AVG'];
    }

    /**
     * 执行sum聚簇函数
     * @param string $field
     * @return mixed|null|false 如果成功则返回相应的值；不存在相应的记录返回null；
     * SQL执行发生错误则返回false
     */
    public function sum($field) {
        $this->statementContext->setFields("SUM({$field}) AS SUM");
        $row = $this->find();

        //SQL执行发生错误
        if ($row === false) {
            return false;
        }

        return $row['SUM'];
    }

    /**
     * 插入一条记录
     * @param array $data 要插入的数据
     * @return string|false 插入成功则返回新插入记录的id，SQL执行发生错误返回false
     */
    public function add($data) {
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
     * 更新数据
     * @param array $data 要更新的数据，关联数组
     * @return int|false 如果成功，则返回受影响的行数；如果SQL执行发生错误，返回false。
     * 注意返回<b>0</b>和返回<b>false</b>的区别。
     */
    public function save($data) {
        $statement = new UpdateStatement($data);
        $statement->setStatementContext($this->statementContext);
        $statement->setCondition($this->condition);
        $sql = $statement->parse();

        $rowCount = $this->connection->execute($sql, $this->statementContext->getParameters());

        $this->clear();

        return $rowCount;
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

}
