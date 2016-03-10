<?php

namespace LumengPHP\Db\Condition;

/**
 * AND 条件
 *
 * @author Lumeng <zhengb302@163.com>
 */
class AndCondition extends CompositeCondition {

    public function parse() {
        $tmpArr = array();
        foreach ($this->conditions as $condition) {
            $tmpArr[] = $condition->parse();
        }
        return '(' . implode(' AND ', $tmpArr) . ')';
    }

}
