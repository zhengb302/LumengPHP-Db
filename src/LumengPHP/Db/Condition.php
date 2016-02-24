<?php

namespace LumengPHP\Db;

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
