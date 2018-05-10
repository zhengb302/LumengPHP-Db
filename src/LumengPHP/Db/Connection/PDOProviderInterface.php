<?php

namespace LumengPHP\Db\Connection;

use PDO;

/**
 * PDO Provider 接口
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
interface PDOProviderInterface {

    /**
     * 服务器类型：主服务器
     */
    const TYPE_MASTER = 0;

    /**
     * 服务器类型：从服务器
     */
    const TYPE_SLAVE = 1;

    /**
     * 返回一个<b>PDO</b>实例
     * 
     * @param int $type 服务器类型
     * @return PDO
     */
    public function getPDO($type = PDOProviderInterface::TYPE_MASTER);
}
