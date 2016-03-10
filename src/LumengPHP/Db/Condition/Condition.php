<?php

namespace LumengPHP\Db\Condition;

use LumengPHP\Db\StatementContext;

/**
 * SQL条件
 *
 * @author zhengluming <luming.zheng@baozun.cn>
 */
interface Condition {

    public function setStatementContext(StatementContext $statementContext);

    /**
     * 
     * @return string 解析出的SQL子句
     */
    public function parse();
}
