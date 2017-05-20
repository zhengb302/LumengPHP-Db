<?php

namespace LumengPHP\Db\Condition;

/**
 * （NOT）BETWEEN 条件
 *
 * @author zhengluming <908235332@qq.com>
 */
class BetweenCondition extends SimpleCondition {

    private $start;
    private $end;

    /**
     * @var boolean 是否带上NOT关键字（带上就是NOT BETWEEN了）
     */
    private $withNot;

    public function __construct($start, $end, $withNot = false) {
        $this->start = $start;
        $this->end = $end;
        $this->withNot = $withNot;
    }

    public function parse() {
        $startPlaceholder = $this->makePlaceholder();
        $this->statementContext->addParameter($startPlaceholder, $this->start);

        $endPlaceholder = $this->makePlaceholder();
        $this->statementContext->addParameter($endPlaceholder, $this->end);

        $symbol = $this->withNot ? 'NOT BETWEEN' : 'BETWEEN';
        return "{$this->field} "
                . "{$symbol} {$startPlaceholder} AND {$endPlaceholder}";
    }

}
