<?php

namespace LumengPHP\Db\Condition;

use LumengPHP\Db\Statement\StatementContext;

/**
 * SQL语句条件接口
 *
 * @author zhengluming <908235332@qq.com>
 */
interface ConditionInterface {

    public function setStatementContext(StatementContext $statementContext);

    /**
     * 
     * @return string 解析出的SQL子句
     */
    public function parse();
}
