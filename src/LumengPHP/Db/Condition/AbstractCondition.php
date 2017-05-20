<?php

namespace LumengPHP\Db\Condition;

use LumengPHP\Db\StatementContext;

/**
 * SQL语句条件抽象基类
 *
 * @author zhengluming <908235332@qq.com>
 */
abstract class AbstractCondition implements ConditionInterface {

    /**
     * @var StatementContext 
     */
    protected $statementContext;

    public function setStatementContext(StatementContext $statementContext) {
        $this->statementContext = $statementContext;
    }

}
