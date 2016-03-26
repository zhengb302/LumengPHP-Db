<?php

namespace LumengPHP\Db\Test;

/**
 * 单元测试 数据库测试用例
 *
 * @author zhengluming <luming.zheng@baozun.cn>
 */
abstract class DatabaseTestCase extends \PHPUnit_Extensions_Database_TestCase {

    /**
     * @var \PHPUnit_Extensions_Database_DB_IDatabaseConnection Only instantiate 
     * PHPUnit_Extensions_Database_DB_IDatabaseConnection once per test
     */
    private $conn = null;

    /**
     * Returns the test database connection.
     * @return \PHPUnit_Extensions_Database_DB_IDatabaseConnection
     */
    public function getConnection() {
        if ($this->conn === null) {
            $this->conn = $this->createDefaultDBConnection($this->getPdo());
        }

        return $this->conn;
    }

    /**
     * 返回用于测试的PDO实例
     * @return \PDO
     */
    abstract public function getPdo();

    /**
     * 创建组合的 Mysql Xml DataSet
     * @param array $xmlFileArray mysql xml文件路径（绝对路劲）数组
     * @return \PHPUnit_Extensions_Database_DataSet_CompositeDataSet
     */
    protected function createCompositeMySQLXMLDataSet($xmlFileArray) {
        $compositeDataSet = new \PHPUnit_Extensions_Database_DataSet_CompositeDataSet();
        foreach ($xmlFileArray as $xmlFile) {
            $dataSet = $this->createMySQLXMLDataSet($xmlFile);
            $compositeDataSet->addDataSet($dataSet);
        }
        return $compositeDataSet;
    }

}
