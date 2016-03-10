<?php

namespace LumengPHP\Db\Condition;

/**
 * 小于（或等于） 条件
 *
 * @author Lumeng <zhengb302@163.com>
 */
class LessThanCondition extends SimpleCondition {

    private $value;

    /**
     * @var boolean 是否小于等于
     */
    private $withEqual;

    public function __construct($value, $withEqual = false) {
        $this->value = $value;
        $this->withEqual = $withEqual;
    }

    public function parse() {
        $placeholder = $this->makePlaceholder();
        $this->statementContext->addParameter($placeholder, $this->value);

        $symbol = $this->withEqual ? '<=' : '<';
        return "{$this->field} {$symbol} {$placeholder}";
    }

}
