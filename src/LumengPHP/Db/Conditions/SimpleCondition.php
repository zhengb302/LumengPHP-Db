<?php

namespace LumengPHP\Db\Conditions;

use \LumengPHP\Db\ConditionBase;

/**
 * 简单条件（相对于复合条件，即CompositeCondition）
 *
 * @author zhengluming <luming.zheng@baozun.cn>
 */
abstract class SimpleCondition extends ConditionBase {

    /**
     * @var string 
     */
    protected $field;

    public function setField($field) {
        $this->field = $field;
    }

    protected function makePlaceholder() {
        return ':' . $this->field . '_' .
                $this->statementContext->getParameterCounter()->getNextNum();
    }

}
