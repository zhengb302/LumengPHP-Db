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
        //条件值为null，很大的可能是未检查过滤用户的输入，直接使用原始的输入值来做查询条件
        //框架采取的做法是抛出异常，提醒开发者要对用户输入做检查
        $conditions = [
            'username' => null,
        ];
        $arrayCondition = new ArrayCondition($conditions);
        $arrayCondition->parse();
    }

}
