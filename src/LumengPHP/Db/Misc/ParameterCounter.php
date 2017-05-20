<?php

namespace LumengPHP\Db\Misc;

/**
 * 参数计数器
 *
 * @author zhengluming <908235332@qq.com>
 */
class ParameterCounter {

    /**
     * @var int 
     */
    private $counter;

    public function __construct() {
        $this->counter = 0;
    }

    public function getNextNum() {
        return $this->counter++;
    }

    public function restart() {
        $this->counter = 0;
    }

}
