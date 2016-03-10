<?php

namespace LumengPHP\Db\Join;

/**
 * Join子句(复合)
 *
 * @author Lumeng <zhengb302@163.com>
 */
class JoinClause {

    /**
     * @var array Join实例数组
     */
    private $joins;

    public function __construct() {
        $this->joins = array();
    }

    public function addJoin(Join $join) {
        $this->joins[] = $join;
    }

    public function parse() {
        if (empty($this->joins)) {
            return '';
        }

        $joinClause = '';
        foreach ($this->joins as $join) {
            $joinClause = $joinClause . $join->parse();
        }

        return $joinClause;
    }

    public function clear() {
        $this->joins = array();
    }

}
