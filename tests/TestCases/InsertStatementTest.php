<?php

namespace tests\TestCases;

use LumengPHP\Db\Statement\StatementContext;

/**
 * 
 *
 * @author Lumeng <zhengb302@163.com>
 */
class InsertStatementTest extends \PHPUnit_Framework_TestCase {

    public function testNormal() {
        $statementContext = new StatementContext();
        $statementContext->setTableName('user');

        $data = array(
            'username' => 'san.zhang',
            'age' => 18,
            'password' => md5('123456'),
        );

        $statement = new \LumengPHP\Db\Statement\InsertStatement($data);
        $statement->setStatementContext($statementContext);
        $sql = $statement->parse();

        $expected = 'INSERT INTO `user`(`username`, `age`, `password`)'
                . ' VALUES(:username_0, :age_1, :password_2)';
        $this->assertEquals($expected, $sql);

        $expectedParameters = array(
            ':username_0' => 'san.zhang',
            ':age_1' => 18,
            ':password_2' => md5('123456'),
        );
        $this->assertEquals($expectedParameters, $statementContext->getParameters());
    }

}
