<?php

namespace LumengPHP\Db\Condition;

/**
 * （NOT）LIKE 条件
 *
 * @author Lumeng <zhengb302@163.com>
 */
class LikeCondition extends SimpleCondition {

    /**
     * @var string LIKE条件的模式，如：20151102%
     */
    private $pattern;

    /**
     * @var boolean 是否带上NOT关键字（带上就是NOT LIKE了）
     */
    private $withNot;

    public function __construct($pattern, $withNot = false) {
        $this->pattern = $pattern;
        $this->withNot = $withNot;
    }

    public function parse() {
        $placeholder = $this->makePlaceholder();
        $this->statementContext->addParameter($placeholder, $this->value);

        $symbol = $this->withNot ? 'NOT LIKE' : 'LIKE';
        return "{$this->field} {$symbol} {$placeholder}";
    }

}
