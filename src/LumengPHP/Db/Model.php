<?php

namespace LumengPHP\Db;

use \PDO;
use LumengPHP\Db\Statements\SelectStatement;
use LumengPHP\Db\Statements\InsertStatement;
use LumengPHP\Db\Statements\UpdateStatement;
use LumengPHP\Db\Statements\DeleteStatement;
use LumengPHP\Db\Conditions\MapCondition;
use LumengPHP\Utils\StringHelper;

/**
 * Model基类
 *
 * @author zhengluming <luming.zheng@baozun.cn>
 */
class Model {

    /**
     * @var ConnectionGroup 所属的数据库连接组
     */
    private $connGroup;

    /**
     * @var StatementContext SQL语句环境
     */
    private $statementContext;

    /**
     * @var string 表名（包含表前缀）
     */
    private $tableName;

    /**
     * @var Condition 条件
     */
    private $condition;

    public function __construct() {
        $this->connGroup = ConnectionManager::getConnectionManager()
                ->getConnectionGroup($this->getGroupName());
        $this->statementContext = new StatementContext();
        $this->statementContext->setTableName($this->getTableName());
    }

    /**
     * 返回本model所属的数据库连接组组名
     * @return string|null
     */
    public function getGroupName() {
        return null;
    }

    /**
     * 返回表名称（包括表前缀）
     * @return string
     */
    protected function getTableName() {
        if (is_null($this->tableName)) {
            $basename = StringHelper::basename(get_called_class());

            //去掉末尾的”Model“
            $nakedTableName = substr($basename, 0, strlen($basename) - 5);

            $tmpName = StringHelper::camel2id($nakedTableName, '_');
            $this->tableName = $this->connGroup->getTablePrefix() . $tmpName;
        }

        return $this->tableName;
    }

    /**
     * 
     * @param string $fields
     * @return Model
     */
    public function field($fields) {
        $this->statementContext->setFields($fields);
        return $this;
    }

    /**
     * 
     * @param array|Condition $condition
     * @return Model
     */
    public function where($condition) {
        if (is_array($condition)) {
            $this->condition = new MapCondition($condition);
        } elseif ($condition instanceof Condition) {
            $this->condition = $condition;
        } else {
            trigger_error('错误的参数类型！', E_USER_ERROR);
        }

        $this->condition->setStatementContext($this->statementContext);

        return $this;
    }

    public function find() {
        $this->statementContext->setLimit(1);

        $statement = new SelectStatement();
        $statement->setStatementContext($this->statementContext);
        $statement->setCondition($this->condition);
        $sql = $statement->parse();

        $conn = $this->getConnection(Connection::OP_READ);

        $pdoStmt = $conn->prepare($sql);
        $pdoStmt->execute($this->statementContext->getParameters());
        $row = $pdoStmt->fetch(PDO::FETCH_ASSOC);

        $this->clear();

        return $row;
    }

    public function select() {
        $statement = new SelectStatement();
        $statement->setStatementContext($this->statementContext);
        $statement->setCondition($this->condition);
        $sql = $statement->parse();

        $conn = $this->getConnection(Connection::OP_READ);

        $pdoStmt = $conn->prepare($sql);
        $pdoStmt->execute($this->statementContext->getParameters());
        $rows = $pdoStmt->fetchAll(PDO::FETCH_ASSOC);

        $this->clear();

        return $rows;
    }

    public function add($data) {
        $statement = new InsertStatement($data);
        $statement->setStatementContext($this->statementContext);
        $sql = $statement->parse();

        $conn = $this->getConnection(Connection::OP_WRITE);

        $pdoStmt = $conn->prepare($sql);
        $pdoStmt->execute($this->statementContext->getParameters());

        $this->clear();

        return $conn->lastInsertId();
    }

    public function save($data) {
        $statement = new UpdateStatement($data);
        $statement->setStatementContext($this->statementContext);
        $statement->setCondition($this->condition);
        $sql = $statement->parse();

        $conn = $this->getConnection(Connection::OP_WRITE);

        $pdoStmt = $conn->prepare($sql);
        $pdoStmt->execute($this->statementContext->getParameters());

        $this->clear();

        return $pdoStmt->rowCount();
    }

    public function delete() {
        $statement = new DeleteStatement();
        $statement->setStatementContext($this->statementContext);
        $statement->setCondition($this->condition);
        $sql = $statement->parse();

        $conn = $this->getConnection(Connection::OP_WRITE);

        $pdoStmt = $conn->prepare($sql);
        $pdoStmt->execute($this->statementContext->getParameters());

        $this->clear();

        return $pdoStmt->rowCount();
    }

    /**
     * 返回数据库连接
     * @param int $operation
     * @return Connection
     */
    protected function getConnection($operation) {
        return $this->connGroup
                        ->selectConnection($operation, $this->getTableName());
    }

    private function clear() {
        $this->statementContext->clear();
    }

}
