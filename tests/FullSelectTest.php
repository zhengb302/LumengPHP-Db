<?php

namespace tests;

use tests\Model\UserModel;

/**
 * 全流程测试 - select
 *
 * @author Lumeng <zheng@163.com>
 */
class FullSelectTest extends BaseDatabaseTestCase {

    protected function getDataSet() {
        $xmlFileArray = array(
            
        );
        return $this->createCompositeMySQLXMLDataSet($xmlFileArray);
    }

    public function testNormal() {
        $conditions = array(
            'username' => 'zheng',
        );

        $userModel = new UserModel();
        $user = $userModel->field('*')->where($conditions)->find();
        $this->assertNotNull($user);
        $this->assertEquals('zheng', $user['username']);
    }

    public function testSelectTwice() {
        $conditions = array(
            'username' => 'zheng',
        );

        $userModel = new UserModel();
        $user = $userModel->field('*')->where($conditions)->find();
        $this->assertNotNull($user);
        $this->assertEquals('zheng', $user['username']);

        $user2 = $userModel->field('*')->where($conditions)->find();
        $this->assertNotNull($user2);
        $this->assertEquals('zheng', $user2['username']);
    }

    public function testCount() {
        $userModel = new UserModel();
        $result = $userModel->count();
        $this->assertEquals(3, $result);
    }

    public function testMax() {
        $userModel = new UserModel();
        $result = $userModel->max('id');
        $this->assertEquals(3, $result);
    }

    public function testMin() {
        $userModel = new UserModel();
        $result = $userModel->min('id');
        $this->assertEquals(1, $result);
    }

    public function testAvg() {
        $userModel = new UserModel();
        $result = $userModel->avg('id');
        $this->assertEquals(2, $result);
    }

    public function testSum() {
        $userModel = new UserModel();
        $result = $userModel->sum('id');
        $this->assertEquals(6, $result);
    }

}
