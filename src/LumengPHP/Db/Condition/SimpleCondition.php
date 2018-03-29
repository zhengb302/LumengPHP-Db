<?php

namespace LumengPHP\Db\Condition;

use LumengPHP\Db\Misc\FieldHelper;

/**
 * 简单条件（相对于复合条件，即CompositeCondition）
 *
 * @author zhengluming <908235332@qq.com>
 */
abstract class SimpleCondition extends AbstractCondition {

    /**
     * @var string 原始字段名称
     */
    protected $rawField;

    /**
     * @var string 加工过之后的字段名称
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
