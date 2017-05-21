<?php

namespace LumengPHP\Db\Condition;

use LumengPHP\Db\Exceptions\InvalidSQLConditionException;

/**
 * 接受一个关联数组
 *
 * @author Lumeng <zhengb302@163.com>
 */
class ArrayCondition extends CompositeCondition {

    /**
     * @var array 子条件数组，是一个关联数组
     */
    protected $conditions;

    public function __construct(array $conditions) {
        $this->conditions = $conditions;
    }

    public function parse() {
        return $this->parseArrayCondition($this->conditions);
    }

    private function parseArrayCondition($arrayCondition) {
        $parsedConditions = [];

        $logic = 'AND';
        foreach ($arrayCondition as $field => $value) {
            //同一级条件下同一个字段出现多次
            if (strpos($field, '#')) {
                list($field, ) = explode('#', $field);
            }

            //如果不是操作符，是普通的条件
            if ($field[0] != '_') {
                $parsedConditions[] = $this->parseSimpleCondition($field, $value);
                continue;
            }

            /*
             * 如果是操作符
             */

            $op = $field;
            $operand = $value;

            //校验一下
            $this->verifyOp($op, $operand);

            if ($op == '_logic') {
                $logic = strtoupper($operand);
            } else {
                $parsedConditions[] = $this->parseOp($op, $operand);
            }
        }

        return '(' . implode(" {$logic} ", $parsedConditions) . ')';
    }

    private function verifyOp($op, $operand) {
        switch ($op) {
            case '_logic':
                if (!in_array($operand, ['and', 'or'])) {
                    $errMsg = '逻辑连接词必须是and或or';
                    throw new InvalidSQLConditionException($errMsg);
                }
                break;
            case '_sub':
                if (!is_array($operand)) {
                    $errMsg = '子条件的值必须是数组';
                    throw new InvalidSQLConditionException($errMsg);
                }
                break;
            case '_string':
                if (!is_string($operand)) {
                    $errMsg = '原生SQL条件必须是字符串';
                    throw new InvalidSQLConditionException($errMsg);
                }
                break;
            default:
                $errMsg = "不支持的操作符：{$op}";
                throw new InvalidSQLConditionException($errMsg);
        }
    }

    private function parseOp($op, $operand) {
        if ($op == '_sub') {
            return $this->parseArrayCondition($operand);
        } elseif ($op == '_string') {
            return '(' . $operand . ')';
        }
    }

    /**
     * 
     * @param string $field 
     * @param mixed $value
     * @return string
     */
    private function parseSimpleCondition($field, $value) {
        switch (gettype($value)) {
            case 'integer':
            case 'double':
            case 'float':
            case 'string':
                $condition = new EqualCondition($value);
                break;
            case 'array':
                $condition = $this->buildOtherSimpleCondition($value);
                break;
        }

        $condition->setField($field);
        $condition->setStatementContext($this->statementContext);

        return $condition->parse();
    }

    private function buildOtherSimpleCondition($arrayVal) {
        $type = $arrayVal[0];
        $value = $arrayVal[1];
        switch ($type) {
            case 'eq':
                $condition = new EqualCondition($value);
                break;
            case 'neq':
                $condition = new NotEqualCondition($value);
                break;
            case 'gt':
                $condition = new GreaterThanCondition($value);
                break;
            case 'gte':
                $condition = new GreaterThanCondition($value, true);
                break;
            case 'lt':
                $condition = new LessThanCondition($value);
                break;
            case 'lte':
                $condition = new LessThanCondition($value, true);
                break;
            case 'in':
                $condition = new InCondition($value);
                break;
            case 'not in':
                $condition = new InCondition($value, true);
                break;
            case 'between':
                $start = $value;
                $end = $arrayVal[2];
                $condition = new BetweenCondition($start, $end);
                break;
            case 'not between':
                $start = $value;
                $end = $arrayVal[2];
                $condition = new BetweenCondition($start, $end, true);
                break;
            case 'like':
                $condition = new LikeCondition($value);
                break;
            case 'not like':
                $condition = new LikeCondition($value, true);
                break;
            default:
                $errMsg = "不支持的条件类型：{$type}";
                throw new InvalidSQLConditionException($errMsg);
        }

        return $condition;
    }

}
