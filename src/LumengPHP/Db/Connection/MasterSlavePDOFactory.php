<?php

namespace LumengPHP\Db\Connection;

use LumengPHP\Db\Exception\SqlException;
use PDO;

/**
 * 主从模式的PDO工厂类
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class MasterSlavePDOFactory extends AbstractPDOFactory {

    /**
     * @var PDO 主数据库连接
     */
    private $masterPdo;

    /**
     * @var PDO 从数据库连接
     */
    private $slavePdo;

    public function getPDO($type = PDOFactoryInterface::TYPE_MASTER) {
        switch ($type) {
            case self::TYPE_MASTER:
                return $this->getMasterPdo();
            case self::TYPE_SLAVE:
                return $this->selectSlavePdo();
        }
    }

    /**
     * 获取Master连接
     * @return ConnectionInterface
     */
    private function getMasterPdo() {
        if (!is_null($this->masterPdo)) {
            return $this->masterPdo;
        }

        //第一个为master服务器
        $config = $this->config['servers'][0];

        $dsn = $this->makeDsn($config['host'], $config['port'], $config['dbName']);
        $username = $config['username'];
        $password = $config['password'];
        $this->masterPdo = $this->makePdo($dsn, $username, $password);

        return $this->masterPdo;
    }

    private function selectSlavePdo() {
        if (!is_null($this->slavePdo)) {
            return $this->slavePdo;
        }

        $serverNum = count($this->config['servers']);
        if ($serverNum == 0) {
            throw new SqlException("MasterSlavePDOFactory：服务器列表不能为空");
        }

        //只有一台服务器
        if ($serverNum == 1) {
            $config = $this->config['servers'][0];
        }
        //有多于一台服务器，从"从服务器"中随机选取一台
        else {
            $slaveIndex = mt_rand(1, $serverNum - 1);
            $config = $this->config['servers'][$slaveIndex];
        }

        $dsn = $this->makeDsn($config['host'], $config['port'], $config['dbName']);
        $username = $config['username'];
        $password = $config['password'];
        $this->slavePdo = $this->makePdo($dsn, $username, $password);

        return $this->slavePdo;
    }

}
