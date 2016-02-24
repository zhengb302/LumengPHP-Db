<?php

namespace LumengPHP\Db;

/**
 * 条件基类
 *
 * @author zhengluming <luming.zheng@baozun.cn>
 */
abstract class ConditionBase implements Condition {

    /**
     * @var StatementContext 
     */
    protected $statementContext;

    public function setStatementContext(StatementContext $statementContext) {
        $this->statementContext = $statementContext;
    }

}
