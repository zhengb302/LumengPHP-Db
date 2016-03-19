<?php

/*
 * SQL条件 快捷函数
 */

use LumengPHP\Db\Condition\Condition;
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
 *   $andCond = sqlAnd(array($cond1, $cond2));
 * @param array $conditions 各子条件
 * @return Condition
 */
function sqlAnd(array $conditions) {
    $andCondition = new AndCondition();
    foreach ($conditions as $condition) {
        $andCondition->add($condition);
    }
    return $andCondition;
}

/**
 * 
 * @param array $conditions 各子条件
 * @return Condition
 */
function sqlOr(array $conditions) {
    $orCondition = new OrCondition();
    foreach ($conditions as $condition) {
        $orCondition->add($condition);
    }
    return $orCondition;
}

/**
 * 
 * @param mixed $start
 * @param mixed $end
 * @return Condition
 */
function sqlBetween($start, $end) {
    return new BetweenCondition($start, $end);
}

/**
 * 
 * @param mixed $start
 * @param mixed $end
 * @return Condition
 */
function sqlNotBetween($start, $end) {
    return new BetweenCondition($start, $end, true);
}

/**
 * 
 * @param mixed $value
 * @return Condition
 */
function sqlEQ($value) {
    return new EqualCondition($value);
}

/**
 * 
 * @param mixed $value
 * @return Condition
 */
function sqlNEQ($value) {
    return new NotEqualCondition($value);
}

/**
 * 
 * @param mixed $value
 * @return Condition
 */
function sqlGT($value) {
    return new GreaterThanCondition($value);
}

/**
 * 
 * @param mixed $value
 * @return Condition
 */
function sqlEGT($value) {
    return new GreaterThanCondition($value, true);
}

/**
 * 
 * @param array $candidates
 * @return Condition
 */
function sqlIn(array $candidates) {
    return new InCondition($candidates);
}

/**
 * 
 * @param array $candidates
 * @return Condition
 */
function sqlNotIn(array $candidates) {
    return new InCondition($candidates, true);
}

/**
 * 
 * @param mixed $value
 * @return Condition
 */
function sqlLT($value) {
    return new LessThanCondition($value);
}

/**
 * 
 * @param mixed $value
 * @return Condition
 */
function sqlELT($value) {
    return new LessThanCondition($value, true);
}

/**
 * 
 * @param string $pattern
 * @return Condition
 */
function sqlLike($pattern) {
    return new LikeCondition($pattern);
}

/**
 * 
 * @param string $pattern
 * @return Condition
 */
function sqlNotLike($pattern) {
    return new LikeCondition($pattern, true);
}

/**
 * 
 * @param string $subSql
 * @return Condition
 */
function sqlExists($subSql) {
    return new ExistsCondition($subSql);
}

/**
 * 
 * @param string $subSql
 * @return Condition
 */
function sqlNotExists($subSql) {
    return new ExistsCondition($subSql, true);
}
