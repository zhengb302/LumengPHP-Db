<?php

namespace tests;

/**
 * 
 *
 * @author zhengluming <luming.zheng@baozun.cn>
 */
class SelectStatementTest extends \PHPUnit_Framework_TestCase {

    public function testNormal() {
        $statementContext = new \LumengPHP\Db\StatementContext();
        $statementContext->setFields('id,user_id,add_time');
        $statementContext->setTableName('user');

        $condition = new \LumengPHP\Db\Conditions\MapCondition(array(
            'id' => 197,
            'user_id' => sqlIn(array(2, 8, 7, 6)),
            'add_time' => sqlBetween(strtotime('yesterday'), time()),
        ));
        $condition->setStatementContext($statementContext);

        $statement = new \LumengPHP\Db\Statements\SelectStatement();
        $statement->setStatementContext($statementContext);
        $statement->setCondition($condition);
        $sql = $statement->parse();
        $this->assertEquals('SELECT id,user_id,add_time FROM user WHERE (id = :id_0 AND user_id IN (:user_id_1, :user_id_2, :user_id_3, :user_id_4) AND add_time BETWEEN :add_time_5 AND :add_time_6)', $sql);
    }

}
