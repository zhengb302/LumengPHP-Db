<?php

/*
 * SQL条件 快捷函数
 */

use LumengPHP\Db\Condition\ConditionInterface;
use LumengPHP\Db\Condition\AndCondition;
use LumengPHP\Db\Condition\OrCondition;
use LumengPHP\Db\Condition\BetweenCondition;
use LumengPHP\Db\Condition\InCondition;
use LumengPHP\Db\Condition\EqualCondition;
use LumengPHP\Db\Condition\NotEqualCondition;
use LumengPHP\Db\Condition\GreaterThanCondition;
use LumengPHP\Db\Condition\LessThanCondition;
use LumengPHP\Db\Condition\LikeCondition;
use LumengPHP\Db\Condition\ExistsCondition;

/**
 * Usage:
 *   $cond1 = ...
 *   $cond2 = ...
 *   $andCond = sqlAnd(array(
 *       $cond1,
 *       $cond2,
 *       'username' => 'hanmeimei',
 *   ));
 * @param array $conditions 各子条件
 * @return ConditionInterface
 */
function sqlAnd(array $conditions) {
    return new AndCondition($conditions);
}

/**
 * 
 * @param array $conditions 各子条件
 * @return ConditionInterface
 */
function sqlOr(array $conditions) {
    return new OrCondition($conditions);
}

/**
 * 
 * @param mixed $start
 * @param mixed $end
 * @return ConditionInterface
 */
function sqlBetween($start, $end) {
    return new BetweenCondition($start, $end);
}

/**
 * 
 * @param mixed $start
 * @param mixed $end
 * @return ConditionInterface
 */
function sqlNotBetween($start, $end) {
    return new BetweenCondition($start, $end, true);
}

/**
 * 
 * @param mixed $value
 * @return ConditionInterface
 */
function sqlEQ($value) {
    return new EqualCondition($value);
}

/**
 * 
 * @param mixed $value
 * @return ConditionInterface
 */
function sqlNEQ($value) {
    return new NotEqualCondition($value);
}

/**
 * 
 * @param mixed $value
 * @return ConditionInterface
 */
function sqlGT($value) {
    return new GreaterThanCondition($value);
}

/**
 * 
 * @param mixed $value
 * @return ConditionInterface
 */
function sqlEGT($value) {
    return new GreaterThanCondition($value, true);
}

/**
 * 
 * @param array $candidates
 * @return ConditionInterface
 */
function sqlIn(array $candidates) {
    return new InCondition($candidates);
}

/**
 * 
 * @param array $candidates
 * @return ConditionInterface
 */
function sqlNotIn(array $candidates) {
    return new InCondition($candidates, true);
}

/**
 * 
 * @param mixed $value
 * @return ConditionInterface
 */
function sqlLT($value) {
    return new LessThanCondition($value);
}

/**
 * 
 * @param mixed $value
 * @return ConditionInterface
 */
function sqlELT($value) {
    return new LessThanCondition($value, true);
}

/**
 * 
 * @param string $pattern
 * @return ConditionInterface
 */
function sqlLike($pattern) {
    return new LikeCondition($pattern);
}

/**
 * 
 * @param string $pattern
 * @return ConditionInterface
 */
function sqlNotLike($pattern) {
    return new LikeCondition($pattern, true);
}

/**
 * 
 * @param string $subSql
 * @return ConditionInterface
 */
function sqlExists($subSql) {
    return new ExistsCondition($subSql);
}

/**
 * 
 * @param string $subSql
 * @return ConditionInterface
 */
function sqlNotExists($subSql) {
    return new ExistsCondition($subSql, true);
}
