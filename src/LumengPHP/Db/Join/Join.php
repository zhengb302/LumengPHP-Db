<?php

namespace LumengPHP\Db\Join;

/**
 * Join子句
 *
 * @author Lumeng <zhengb302@163.com>
 */
class Join {

    const INNER_JOIN = 0;
    const LEFT_JOIN = 1;
    const RIGHT_JOIN = 2;

    /**
     * @var string 真实表名，包括表前缀
     */
    private $table;

    /**
     * @var string 表别名
     */
    private $tableAlias;

    /**
     * @var string 连接条件
     */
    private $on;

    public function __construct($table, $tableAlias, $on) {
        $this->table = $table;
        $this->tableAlias = $tableAlias;
        $this->on = $on;
    }

    public function parse() {
        
    }

}
