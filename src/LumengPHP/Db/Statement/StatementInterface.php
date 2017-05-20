<?php

namespace LumengPHP\Db\Statement;

/**
 * SQL语句接口
 *
 * @author Lumeng <zhengb302@163.com>
 */
interface StatementInterface {

    /**
     * 
     * @return string
     */
    public function parse();
}
