<?php

namespace LumengPHP\Db\Conditions;

use \LumengPHP\Db\Condition;
use \LumengPHP\Db\ConditionBase;

/**
 * 关联数组条件
 *
 * @author zhengluming <luming.zheng@baozun.cn>
 */
class MapCondition extends ConditionBase {

    /**
     * @var array 关联数组，key是字段名称，value是字段值
     */
    private $map;

    public function __construct(array $map) {
        $this->map = $map;
    }

    public function parse() {
        $andCondition = new AndCondition();
        foreach ($this->map as $field => $value) {
            $condition = $this->buildCondition($field, $value);
            $condition->setStatementContext($this->statementContext);
            $andCondition->add($condition);
        }

        return $andCondition->parse();
    }

    /**
     * 
     * @param string $field
     * @param mixed $value
     * @return Condition
     */
    private function buildCondition($field, $value) {
        switch (gettype($value)) {
            case 'object':
                if (!($value instanceof Condition)) {
                    trigger_error('xxxxxxxxxxxxxxxxxxxxxxx', E_USER_ERROR);
                }
                $condition = $value;
                if ($condition instanceof SimpleCondition) {
                    $condition->setField($field);
                }
                break;
            case 'integer':
            case 'double':
            case 'float':
            case 'string':
                $condition = new EqualCondition($value);
                $condition->setField($field);
                break;
        }
        return $condition;
    }

}
