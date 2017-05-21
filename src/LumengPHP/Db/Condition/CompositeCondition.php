<?php

namespace LumengPHP\Db\Condition;

use LumengPHP\Db\Exceptions\InvalidSQLConditionException;

/**
 * 复合条件（相对于简单条件，即SimpleCondition）
 *
 * @author zhengluming <908235332@qq.com>
 */
abstract class CompositeCondition extends AbstractCondition {

    /**
     * @var array 子条件数组，是一个关联数组
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
            //first, resolve
            $condition = $this->resolveCondition($field, $value);

            //then, parse
            $parsedConditions[] = $condition->parse();
        }

        return $parsedConditions;
    }

    /**
     * 解析子条件
     * @param string $field 字段名称/操作符
     * @param mixed $value 字段值
     * @return ConditionInterface 条件对象
     * @throws InvalidSQLConditionException
     */
    private function resolveCondition($field, $value) {
        //字段名称是操作符
        if($field[0]=='_'){
            
        }
        
        $condition = $this->buildCondition($value);

        //这里的$condition必然是SimpleCondition
        $condition->setField($field);

        //复合条件的子条件在“外面”八成是没有被注入过StatementContext对象的
        $condition->setStatementContext($this->statementContext);

        return $condition;
    }

    /**
     * 
     * @param mixed $value
     * @return ConditionInterface
     */
    private function buildCondition($value) {
        switch (gettype($value)) {
            case 'object':
                if (!$value instanceof SimpleCondition) {
                    $errMsg = 'the value of an AndCondition element must be a '
                            . 'SimpleCondition instance if it is a object.';
                    throw new InvalidSQLConditionException($errMsg);
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
