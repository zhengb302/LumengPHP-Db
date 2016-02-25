<?php

namespace LumengPHP\Db;

use \LumengPHP\Db\Misc\FieldHelper;

/**
 * SQL语句基类
 *
 * @author zhengluming <luming.zheng@baozun.cn>
 */
abstract class StatementBase implements Statement {

    /**
     * @var StatementContext 
     */
    protected $statementContext;

    /**
     * @var Condition 
     */
    protected $condition;

    public function setStatementContext(StatementContext $statementContext) {
        $this->statementContext = $statementContext;
    }

    public function setCondition($condition) {
        $this->condition = $condition;
    }

    protected function buildWhere() {
        if (!is_null($this->condition)) {
            return ' WHERE ' . $this->condition->parse();
        }

        return '';
    }

    protected function makePlaceholder($field) {
        return ':' . FieldHelper::makePlaceholderField($field) . '_' .
                $this->statementContext->getParameterCounter()->getNextNum();
    }

}
