<?php

namespace LumengPHP\Db;

use LumengPHP\Db\Misc\TableNameHelper;

/**
 * Model基类
 *
 * @author Lumeng <zhengb302@163.com>
 */
class Model extends DataAccessor {

    public function __construct() {
        $connection = ConnectionManager::getInstance()
                ->getConnection($this->getConnectionName());

        $basename = TableNameHelper::basename(get_called_class());
        //去掉末尾的”Model“，得到的是驼峰风格的表名，如"UserProfile"
        $tableName = substr($basename, 0, strlen($basename) - 5);

        parent::__construct($connection, $tableName);
    }

    /**
     * 返回本model所属的数据库连接名称。子类可以覆盖此方法以自定义model所属的连接。
     * @return string|null 如果返回null，则会使得当前model使用默认连接
     */
    public function getConnectionName() {
        return null;
    }

}
