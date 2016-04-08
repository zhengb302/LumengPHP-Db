<?php

namespace LumengPHP\Db\Condition;

/**
 * AND 条件
 *
 * @author Lumeng <zhengb302@163.com>
 */
class AndCondition extends CompositeCondition {

    public function parse() {
        $parsedConditions = $this->resolveAndParseConditions();
        return '(' . implode(' AND ', $parsedConditions) . ')';
    }

}
