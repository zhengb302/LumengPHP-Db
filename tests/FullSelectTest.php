<?php

namespace tests;

use tests\Model\UserModel;

/**
 * 全流程测试 - select
 *
 * @author Lumeng <zhengb302@163.com>
 */
class FullSelectTest extends \PHPUnit_Framework_TestCase {

    public function testNormal() {
        $conditions = array(
            'username' => 'zhengb302',
        );

        $userModel = new UserModel();
        $user = $userModel->field('*')->where($conditions)->find();
        $this->assertNotNull($user);
        $this->assertEquals('zhengb302', $user['username']);
    }

    public function testSelectTwice() {
        $conditions = array(
            'username' => 'zhengb302',
        );

        $userModel = new UserModel();
        $user = $userModel->field('*')->where($conditions)->find();
        $this->assertNotNull($user);
        $this->assertEquals('zhengb302', $user['username']);

        $user2 = $userModel->field('*')->where($conditions)->find();
        $this->assertNotNull($user2);
        $this->assertEquals('zhengb302', $user2['username']);
    }

}
