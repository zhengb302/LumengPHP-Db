<?php

namespace LumengPHP\Db\Condition;

/**
 * 相等条件，如：name = 'zhangsan'
 *
 * @author zhengluming <luming.zheng@baozun.cn>
 */
class EqualCondition extends SimpleCondition {

    private $value;

    public function __construct($value) {
        $this->value = $value;
    }

    public function parse() {
        $placeholder = $this->makePlaceholder();
        $this->statementContext->addParameter($placeholder, $this->value);
        return "{$this->field} = {$placeholder}";
    }

}
