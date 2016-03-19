<?php

namespace LumengPHP\Db\Condition;

/**
 * （NOT）EXISTS 条件
 *
 * @author Lumeng <zhengb302@163.com>
 */
class ExistsCondition extends ConditionBase {

    /**
     * @var string （NOT）EXISTS条件的sql子句
     */
    private $subSql;

    /**
     * @var boolean 是否带上NOT关键字（带上就是NOT EXISTS了）
     */
    private $withNot;

    public function __construct($subSql, $withNot = false) {
        $this->subSql = $subSql;
        $this->withNot = $withNot;
    }

    public function parse() {
        $symbol = $this->withNot ? 'NOT EXISTS' : 'EXISTS';
        return "{$symbol} ({$this->subSql})";
    }

}
