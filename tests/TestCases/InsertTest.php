<?php

namespace tests\TestCases;

use tests\Model\UserModel;

/**
 * 数据插入测试
 *
 * @author Lumeng <zhengb302@163.com>
 */
class InsertTest extends BaseDatabaseTestCase {

    protected function getDataSet() {
        $xmlFileArray = array(
            TEST_ROOT . '/resources/insert-fixture.xml',
        );
        return $this->createCompositeMySQLXMLDataSet($xmlFileArray);
    }

    public function testInsert() {
        $userModel = new UserModel();

        $data = array(
            'username' => 'zhangsan',
            'password' => md5('123456'),
            'email' => 'zhangsan@126.com',
            'nickname' => '张三',
            'add_time' => '1409305283',
        );

        $newUid = $userModel->insert($data);
        $this->assertEquals(4, $newUid);

        $newUser = $userModel->where(array('uid' => $newUid))->findOne();
        $this->assertEquals('张三', $newUser['nickname']);
    }

}
