<?php

namespace LumengPHP\Db\Condition;

/**
 * （NOT）IN 条件
 *
 * @author zhengluming <luming.zheng@baozun.cn>
 */
class InCondition extends SimpleCondition {

    /**
     * @var array 候选条件
     */
    private $candidates;

    /**
     * @var boolean 是否带上NOT关键字（带上就是NOT IN了）
     */
    private $withNot;

    public function __construct(array $candidates, $withNot = false) {
        $this->candidates = $candidates;
        $this->withNot = $withNot;
    }

    public function parse() {
        $placeholders = array();
        foreach ($this->candidates as $candidate) {
            $placeholder = $this->makePlaceholder();
            $this->statementContext->addParameter($placeholder, $candidate);
            $placeholders[] = $placeholder;
        }

        $symbol = $this->withNot ? 'NOT IN' : 'IN';
        $joinedPlaceholders = implode(', ', $placeholders);
        return "{$this->field} {$symbol} ({$joinedPlaceholders})";
    }

}
