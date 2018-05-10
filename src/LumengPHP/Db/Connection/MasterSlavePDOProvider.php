<?php

namespace LumengPHP\Db\Connection;

use PDO;

/**
 * 主从模式的 PDO Provider
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class MasterSlavePDOProvider extends AbstractPDOProvider {

    /**
     * @var PDO 主数据库连接
     */
    private $masterPdo;

    /**
     * @var PDO 从数据库连接
     */
    private $slavePdo;

    public function getMasterPDO() {
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

    public function getSlavePDO() {
        if (!is_null($this->slavePdo)) {
            return $this->slavePdo;
        }

        //使用者需要在配置里确保服务器数量大于等于1
        $serverNum = count($this->config['servers']);

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
