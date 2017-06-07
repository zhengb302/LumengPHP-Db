<?php

namespace tests\TestCases;

use LumengPHP\Db\Statement\StatementContext;
use LumengPHP\Db\Condition\ArrayCondition;
use LumengPHP\Db\Statement\SelectStatement;

/**
 * 
 *
 * @author zhengluming <luming.zheng@baozun.cn>
 */
class SelectStatementTest extends \PHPUnit_Framework_TestCase {

    public function testNormal() {
        $statementContext = new StatementContext();
        $statementContext->setFields('id,user_id,add_time');
        $statementContext->setTableName('user');

        $condition = new ArrayCondition(array(
            'id' => 197,
            'user_id' => ['in', [2, 8, 7]],
            'add_time' => ['between', [strtotime('yesterday'), time()]],
        ));
        $condition->setStatementContext($statementContext);

        $statement = new SelectStatement();
        $statement->setStatementContext($statementContext);
        $statement->setCondition($condition);

        $expectedSql = 'SELECT `id`, `user_id`, `add_time` FROM'
                . ' `user`'
                . ' WHERE (`id` = :id_0 AND'
                . ' `user_id` IN (:user_id_1, :user_id_2, :user_id_3) AND'
                . ' `add_time` BETWEEN :add_time_4 AND :add_time_5)';
        $sql = $statement->parse();
        $this->assertEquals($expectedSql, $sql);
    }

    public function testFieldsWithAlias() {
        $statementContext = new StatementContext();
        $fields = 'id AS uid, user_name userName  ,add_time   addTime';
        $statementContext->setFields($fields);
        $statementContext->setTableName('user');

        $condition = new ArrayCondition(array(
            'id' => 197,
        ));
        $condition->setStatementContext($statementContext);

        $statement = new SelectStatement();
        $statement->setStatementContext($statementContext);
        $statement->setCondition($condition);

        $expectedSql = 'SELECT id AS uid, user_name userName, add_time   addTime FROM'
                . ' `user`'
                . ' WHERE (`id` = :id_0)';
        $sql = $statement->parse();
        $this->assertEquals($expectedSql, $sql);
    }

}
