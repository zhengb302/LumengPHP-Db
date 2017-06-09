<?php

namespace tests\TestCases;

use LumengPHP\Db\Test\DatabaseTestCase;

/**
 * 
 *
 * @author Lumeng <zhengb302@163.com>
 */
abstract class BaseDatabaseTestCase extends DatabaseTestCase {

    /**
     * @var \PDO PDO instance for test
     */
    private static $pdo;

    public function getPdo() {
        if (self::$pdo === null) {
            global $connectionConfigs;

            $config = $connectionConfigs['db1'];
            $host = $config['host'];
            $port = $config['port'];
            $dbName = $config['dbName'];

            //create a PDO instance for test
            $dsn = "mysql:host={$host};port={$port};dbname={$dbName}";
            $username = $config['username'];
            $password = $config['password'];
            self::$pdo = new \PDO($dsn, $username, $password);

            //set charset for test database
            $dbCharset = $config['charset'];
            self::$pdo->query("SET NAMES {$dbCharset}");
        }

        return self::$pdo;
    }

}
