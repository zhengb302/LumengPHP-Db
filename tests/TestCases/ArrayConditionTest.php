<?php

namespace tests\TestCases;

use LumengPHP\Db\Condition\ArrayCondition;

/**
 * ArrayCondition的测试
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class ArrayConditionTest extends \PHPUnit_Framework_TestCase {

    /**
     * 
     * @expectedException \LumengPHP\Db\Exception\InvalidSQLConditionException
     * @expectedExceptionMessage 无效的条件值类型，字段名称：username，值类型：NULL
     */
    public function testNullValue() {
        $conditions = [
            'username' => null,
        ];
        $arrayCondition = new ArrayCondition($conditions);
        $arrayCondition->parse();
    }

}
