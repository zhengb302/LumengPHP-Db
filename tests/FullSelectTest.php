<?php

namespace tests;

use tests\Model\UserModel;

/**
 * 全流程测试 - select
 *
 * @author Lumeng <zhengb302@163.com>
 */
class FullSelectTest extends BaseDatabaseTestCase {

    protected function getDataSet() {
        $xmlFileArray = array(
            TEST_ROOT . '/resources/full-select-fixture.xml',
        );
        return $this->createCompositeMySQLXMLDataSet($xmlFileArray);
    }

    public function testNormal() {
        $conditions = array(
            'username' => 'xiaoming',
        );

        $userModel = new UserModel();
        $user = $userModel->field('*')->where($conditions)->find();
        $this->assertNotNull($user);
        $this->assertEquals('xiaoming', $user['username']);
    }

    public function testSelectTwice() {
        $conditions = array(
            'username' => 'xiaoming',
        );

        $userModel = new UserModel();
        $user = $userModel->field('*')->where($conditions)->find();
        $this->assertNotNull($user);
        $this->assertEquals('xiaoming', $user['username']);

        $user2 = $userModel->field('*')->where($conditions)->find();
        $this->assertNotNull($user2);
        $this->assertEquals('xiaoming', $user2['username']);
    }

    public function testCount() {
        $userModel = new UserModel();
        $result = $userModel->count();
        $this->assertEquals(3, $result);
    }

    public function testMax() {
        $userModel = new UserModel();
        $result = $userModel->max('uid');
        $this->assertEquals(3, $result);
    }

    public function testMin() {
        $userModel = new UserModel();
        $result = $userModel->min('uid');
        $this->assertEquals(1, $result);
    }

    public function testAvg() {
        $userModel = new UserModel();
        $result = $userModel->avg('uid');
        $this->assertEquals(2, $result);
    }

    public function testSum() {
        $userModel = new UserModel();
        $result = $userModel->sum('uid');
        $this->assertEquals(6, $result);
    }

}
