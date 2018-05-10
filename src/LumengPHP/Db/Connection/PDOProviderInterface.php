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
     * 返回<b>主</b>服务器的 PDO 实例
     * 
     * @return PDO
     */
    public function getMasterPDO();

    /**
     * 返回<b>从</b>服务器的 PDO 实例
     * 
     * @return PDO
     */
    public function getSlavePDO();
}
