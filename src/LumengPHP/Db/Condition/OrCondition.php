<?php

namespace LumengPHP\Db\Condition;

/**
 * OR 条件
 *
 * @author Lumeng <zhengb302@163.com>
 */
class OrCondition extends CompositeCondition {

    public function parse() {
        $parsedConditions = $this->resolveAndParseConditions();
        return '(' . implode(' OR ', $parsedConditions) . ')';
    }

}
