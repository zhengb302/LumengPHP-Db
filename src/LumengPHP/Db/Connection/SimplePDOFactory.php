<?php

namespace LumengPHP\Db\Connection;

use PDO;

/**
 * 单一数据库模式的PDO工厂类
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class SimplePDOFactory extends AbstractPDOFactory {

    /**
     * @var PDO 
     */
    private $pdo;

    public function getPDO($type = PDOFactoryInterface::TYPE_MASTER) {
        if (!is_null($this->pdo)) {
            return $this->pdo;
        }

        $dsn = $this->makeDsn($this->config['host'], $this->config['port'], $this->config['dbName']);
        $username = $this->config['username'];
        $password = $this->config['password'];
        $this->pdo = $this->makePdo($dsn, $username, $password);

        return $this->pdo;
    }

}
