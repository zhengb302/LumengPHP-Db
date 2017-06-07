<?php

namespace tests\Model;

/**
 * Model基类
 *
 * @author Lumeng <zhengb302@163.com>
 */
abstract class BaseModel {

    public function __construct() {
        global $connManager;
        parent::__construct($connManager);
    }

}
