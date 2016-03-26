<?php

namespace LumengPHP\Db\Condition;

/**
 * 关联数组条件，如：
 * array(
 *     'uid' => 3,
 *     'is_deleted' => 0,
 * );
 *
 * @author zhengluming <luming.zheng@baozun.cn>
 */
class MapCondition extends BaseCondition {

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
            $condition = $this->buildCondition($value);
            $condition->setStatementContext($this->statementContext);

            //这里的$condition必然是SimpleCondition
            $condition->setField($field);

            $andCondition->add($condition);
        }

        return $andCondition->parse();
    }

    /**
     * 
     * @param mixed $value
     * @return Condition
     */
    private function buildCondition($value) {
        switch (gettype($value)) {
            case 'object':
                if (!($value instanceof SimpleCondition)) {
                    $errMsg = 'the value of a MapCondition element must be a '
                            . 'SimpleCondition instance if it is a object.';
                    trigger_error($errMsg, E_USER_ERROR);
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
