<?php

namespace LumengPHP\Db\Conditions;

use LumengPHP\Db\Condition;
use LumengPHP\Db\ConditionBase;

/**
 * 复合条件（相对于简单条件，即SimpleCondition）
 *
 * @author zhengluming <luming.zheng@baozun.cn>
 */
abstract class CompositeCondition extends ConditionBase {

    /**
     * @var array 
     */
    protected $conditions;

    public function __construct() {
        $this->conditions = array();
    }

    public function add(Condition $condition) {
        $this->conditions[] = $condition;
    }

}
