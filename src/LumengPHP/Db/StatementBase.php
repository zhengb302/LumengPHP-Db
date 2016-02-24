<?php

namespace LumengPHP\Db;

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

    public function setCondition(Condition $condition) {
        $this->condition = $condition;
    }

    protected function makePlaceholder($field) {
        return ':' . $field . '_' .
                $this->statementContext->getParameterCounter()->getNextNum();
    }

}
