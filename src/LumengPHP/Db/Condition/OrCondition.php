<?php

namespace LumengPHP\Db\Condition;

/**
 * OR 条件
 *
 * @author Lumeng <zhengb302@163.com>
 */
class OrCondition extends CompositeCondition {

    public function parse() {
        $tmpArr = array();
        foreach ($this->conditions as $condition) {
            $tmpArr[] = $condition->parse();
        }
        return '(' . implode(' OR ', $tmpArr) . ')';
    }

}
