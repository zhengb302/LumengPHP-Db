<?php

namespace LumengPHP\Db\Condition;

use LumengPHP\Db\Misc\FieldHelper;

/**
 * 简单条件（相对于复合条件，即CompositeCondition）
 *
 * @author zhengluming <luming.zheng@baozun.cn>
 */
abstract class SimpleCondition extends BaseCondition {

    /**
     * @var string 原始字段值
     */
    protected $rawField;

    /**
     * @var string 加工过之后的字段值
     */
    protected $field;

    public function setField($field) {
        $this->rawField = $field;

        $this->field = FieldHelper::quoteField($field);
    }

    protected function makePlaceholder() {
        $field = FieldHelper::makePlaceholderField($this->rawField);
        return ':' . $field . '_' .
                $this->statementContext->getParameterCounter()->getNextNum();
    }

}
