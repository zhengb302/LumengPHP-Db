<?php

/*
 * SQL条件 快捷函数
 */

use LumengPHP\Db\Condition;
use LumengPHP\Db\Conditions\AndCondition;
use LumengPHP\Db\Conditions\OrCondition;
use LumengPHP\Db\Conditions\BetweenCondition;
use LumengPHP\Db\Conditions\InCondition;
use LumengPHP\Db\Conditions\EqualCondition;
use LumengPHP\Db\Conditions\NotEqualCondition;
use LumengPHP\Db\Conditions\GreaterThanCondition;
use LumengPHP\Db\Conditions\LessThanCondition;
use LumengPHP\Db\Conditions\LikeCondition;

/**
 * 
 * @param array $conditions
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
 * @param array $conditions
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
