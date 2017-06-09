<?php

namespace LumengPHP\Db\Condition;

/**
 * 不相等条件
 *
 * @author Lumeng <zhengb302@163.com>
 */
class NotEqualCondition extends SimpleCondition {

    private $value;

    public function __construct($value) {
        $this->value = $value;
    }

    public function parse() {
        $placeholder = $this->makePlaceholder();
        $this->statementContext->addParameter($placeholder, $this->value);
        return "{$this->field} != {$placeholder}";
    }

}
