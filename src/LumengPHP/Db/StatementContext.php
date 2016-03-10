<?php

namespace LumengPHP\Db;

use LumengPHP\Db\Join\JoinClause;
use LumengPHP\Db\Misc\ParameterCounter;

/**
 * SQL语句环境
 *
 * @author zhengluming <luming.zheng@baozun.cn>
 */
class StatementContext {

    /**
     * @var string 以英文逗号分隔的字段列表
     */
    private $fields;

    /**
     * @var string 真实表名
     */
    private $tableName;

    /**
     * @var string 表别名
     */
    private $alias;

    /**
     * @var JoinClause join子句
     */
    private $joinClause;

    /**
     * @var int|string mysql的LIMIT子句的值。如：LIMIT 5 或 LIMIT 22382,10
     */
    private $limit;

    /**
     * @var array placeholder => value 形式的预编译SQL语句参数
     */
    private $parameters;

    /**
     * @var ParameterCounter 参数计数器
     */
    private $parameterCounter;

    public function __construct() {
        $this->joinClause = new JoinClause();
        $this->parameters = array();
        $this->parameterCounter = new ParameterCounter();
    }

    public function getFields() {
        return $this->fields;
    }

    public function setFields($fields) {
        $this->fields = $fields;
    }

    public function getTableName() {
        $tableName = "`{$this->tableName}`";
        if (!is_null($this->alias)) {
            $tableName = "{$tableName} AS {$this->alias}";
        }
        return $tableName;
    }

    public function setTableName($tableName) {
        $this->tableName = $tableName;
    }

    /**
     * 
     * @return JoinClause
     */
    public function getJoinClause() {
        return $this->joinClause;
    }

    public function getAlias() {
        return $this->alias;
    }

    public function setAlias($alias) {
        $this->alias = $alias;
    }

    public function getLimit() {
        return $this->limit;
    }

    public function setLimit($limit) {
        $this->limit = $limit;
    }

    public function addParameter($placeholder, $value) {
        $this->parameters[$placeholder] = $value;
    }

    public function getParameters() {
        return $this->parameters;
    }

    /**
     * 返回条件计数器
     * @return ParameterCounter
     */
    public function getParameterCounter() {
        return $this->parameterCounter;
    }

    public function clear() {
        $this->fields = null;
        $this->joinClause->clear();
        $this->alias = null;
        $this->limit = null;
        $this->parameters = array();
        $this->parameterCounter->restart();
    }

}
