<?php

namespace LumengPHP\Db;

use LumengPHP\Utils\StringHelper;

/**
 * Model基类
 *
 * @author Lumeng <zhengb302@163.com>
 */
class Model {

    /**
     * @var DataAccessor 
     */
    private $dataAccessor;

    public function __construct() {
        $connGroup = ConnectionManager::getConnectionManager()
                ->getConnectionGroup($this->getGroupName());

        $basename = StringHelper::basename(get_called_class());
        //去掉末尾的”Model“，得到的是驼峰风格的表名，如"UserProfile"
        $tableName = substr($basename, 0, strlen($basename) - 5);

        $this->dataAccessor = new DataAccessor($connGroup, $tableName);
    }

    /**
     * 返回本model所属的数据库连接组组名。子类可以覆盖此方法以自定义Model所属的连接组。
     * @return string|null 如果返回null，则会使得当前model使用默认连接组
     */
    public function getGroupName() {
        return null;
    }

    public function __call($name, $arguments) {
        if (!method_exists($this->dataAccessor, $name)) {
            trigger_error("您访问的方法\"{$name}\"不存在~", E_USER_ERROR);
        }

        $callable = array($this->dataAccessor, $name);
        return call_user_func_array($callable, $arguments);
    }

}
