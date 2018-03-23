<?php

namespace LumengPHP\Db\Connection;

use PDO;

/**
 * PDO工厂接口
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
interface PDOFactoryInterface {

    /**
     * 类型：主服务器
     */
    const TYPE_MASTER = 0;

    /**
     * 类型：从服务器
     */
    const TYPE_SLAVE = 1;

    /**
     * 返回一个<b>PDO</b>实例
     * 
     * @param int $type
     * @return PDO
     */
    public function getPDO($type = PDOFactoryInterface::TYPE_MASTER);
}
