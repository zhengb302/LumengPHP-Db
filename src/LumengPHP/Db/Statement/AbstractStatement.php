<?php

namespace LumengPHP\Db\Statement;

use LumengPHP\Db\StatementContext;
use LumengPHP\Db\Misc\FieldHelper;

/**
 * SQL语句抽象基类
 *
 * @author zhengluming <908235332@qq.com>
 */
abstract class AbstractStatement implements StatementInterface {

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
