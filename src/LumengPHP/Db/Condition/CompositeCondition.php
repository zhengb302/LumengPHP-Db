<?php

namespace LumengPHP\Db\Condition;

use LumengPHP\Db\Exceptions\InvalidConditionException;

/**
 * 复合条件（相对于简单条件，即SimpleCondition）
 *
 * @author zhengluming <luming.zheng@baozun.cn>
 */
abstract class CompositeCondition extends BaseCondition {

    /**
     * @var array 子条件数组，是一个混杂着关联数组和下标数组的奇葩数组
     */
    protected $conditions;

    public function __construct(array $conditions) {
        $this->conditions = $conditions;
    }

    /**
     * 解析众子条件并返回一个已解析的子条件数组
     * @return array 已解析的子条件数组(一个字符串数组)
     */
    protected function resolveAndParseConditions() {
        $parsedConditions = array();

        foreach ($this->conditions as $field => $value) {
            $condition = $this->resolveCondition($field, $value);
            $parsedConditions[] = $condition->parse();
        }

        return $parsedConditions;
    }

    /**
     * 解析子条件
     * @param int|string $field 可能是字段名称，也有可能是索引下标
     * @param Condition|mixed $value 可能是字段值，也有可能是Condition对象
     * @return Condition 条件对象
     * @throws InvalidConditionException
     */
    private function resolveCondition($field, $value) {
        if (is_int($field)) {
            if (!$value instanceof Condition) {
                $errMsg = 'the value of an AndCondition element must be a '
                        . 'Condition instance if its index is a integer.';
                throw new InvalidConditionException($errMsg);
            }

            $condition = $value;
        } else {
            $condition = $this->buildCondition($value);
            $condition->setStatementContext($this->statementContext);

            //这里的$condition必然是SimpleCondition
            $condition->setField($field);
        }

        return $condition;
    }

    /**
     * 
     * @param mixed $value
     * @return Condition
     */
    private function buildCondition($value) {
        switch (gettype($value)) {
            case 'object':
                if (!$value instanceof SimpleCondition) {
                    $errMsg = 'the value of an AndCondition element must be a '
                            . 'SimpleCondition instance if it is a object.';
                    throw new InvalidConditionException($errMsg);
                }

                $condition = $value;
                break;
            case 'integer':
            case 'double':
            case 'float':
            case 'string':
                $condition = new EqualCondition($value);
                break;
        }
        return $condition;
    }

}
