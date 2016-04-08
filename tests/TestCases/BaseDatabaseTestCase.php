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

            //create a PDO instance for test
            $dsn = $connectionConfigs['db1']['dsn'];
            $username = $connectionConfigs['db1']['username'];
            $password = $connectionConfigs['db1']['password'];
            self::$pdo = new \PDO($dsn, $username, $password);

            //set charset for test database
            $dbCharset = $connectionConfigs['db1']['charset'];
            self::$pdo->query("SET NAMES {$dbCharset}");
        }

        return self::$pdo;
    }

}
