<?php

namespace LumengPHP\Db;

/**
 * 数据库连接组基类
 *
 * @author Lumeng <zhengb302@163.com>
 */
abstract class ConnectionGroupBase implements ConnectionGroup {

    /**
     * @var string 
     */
    protected $groupName;

    /**
     * @var array
     */
    protected $groupConfig;

    public function __construct($groupName, $groupConfig) {
        $this->groupName = $groupName;
        $this->groupConfig = $groupConfig;
    }

    public function getGroupName() {
        return $this->groupName;
    }

    public function getTablePrefix() {
        return $this->groupConfig['tablePrefix'];
    }

}
