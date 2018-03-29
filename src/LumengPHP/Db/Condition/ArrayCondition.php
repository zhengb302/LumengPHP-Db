<?php

namespace LumengPHP\Db\Condition;

use LumengPHP\Db\Exception\InvalidSQLConditionException;

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

    /**
     * 
     * @param string $field 
     * @param mixed $value
     * @return string
     */
    private function parseSimpleCondition($field, $value) {
        $valType = gettype($value);
        switch ($valType) {
            case 'integer':
            case 'double':
            case 'float':
            case 'string':
                $condition = new EqualCondition($value);
                break;
            case 'array':
                $condition = $this->parseOtherSimpleCondition($value);
                break;
            default:
                throw new InvalidSQLConditionException("无效的条件值类型，字段名称：{$field}，值类型：{$valType}");
        }

        $condition->setField($field);
        $condition->setStatementContext($this->statementContext);

        return $condition->parse();
    }

    private function parseOtherSimpleCondition($arrayVal) {
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
            case '_or':
                if (!is_array($operand)) {
                    $errMsg = '_or操作符的值必须是数组';
                    throw new InvalidSQLConditionException($errMsg);
                }
                break;
            case '_and':
                if (!is_array($operand)) {
                    $errMsg = '_and操作符的值必须是数组';
                    throw new InvalidSQLConditionException($errMsg);
                }
                break;
            case '_string':
                if (!is_string($operand) && !is_array($operand)) {
                    $errMsg = '_string操作符的值必须是字符串或数组';
                    throw new InvalidSQLConditionException($errMsg);
                }
                break;
            default:
                $errMsg = "不支持的操作符：{$op}";
                throw new InvalidSQLConditionException($errMsg);
        }
    }

    private function parseOp($op, $operand) {
        switch ($op) {
            case '_sub':
                //递归调用parseArrayCondition方法
                return $this->parseArrayCondition($operand);
            case '_or':
                return $this->parseOrOp($operand);
            case '_and':
                return $this->parseAndOp($operand);
            case '_string':
                return $this->parseStringOp($operand);
        }
    }

    private function parseOrOp($operand) {
        $subParsedConditions = [];
        foreach ($operand as $subArrayCondition) {
            $subParsedConditions[] = $this->parseArrayCondition($subArrayCondition);
        }
        return '(' . implode(' OR ', $subParsedConditions) . ')';
    }

    private function parseAndOp($operand) {
        $subParsedConditions = [];
        foreach ($operand as $subArrayCondition) {
            $subParsedConditions[] = $this->parseArrayCondition($subArrayCondition);
        }
        return '(' . implode(' AND ', $subParsedConditions) . ')';
    }

    private function parseStringOp($operand) {
        if (is_string($operand)) {
            return '(' . $operand . ')';
        }

        foreach ($operand as $i => $value) {
            $operand[$i] = '(' . $value . ')';
        }
        return '(' . implode(' AND ', $operand) . ')';
    }

}
