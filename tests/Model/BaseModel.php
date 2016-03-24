<?php

namespace tests\Model;

use LumengPHP\Db\Model;

/**
 * Model基类
 *
 * @author Lumeng <zhengb302@163.com>
 */
abstract class BaseModel extends Model {

    public function __construct() {
        global $connManager;
        parent::__construct($connManager);
    }

}
