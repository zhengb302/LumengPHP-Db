<?php

namespace tests\TestCases;

use tests\Model\UserModel;

/**
 * 更新数据测试
 *
 * @author Lumeng <zhengb302@163.com>
 */
class UpdateTest extends BaseDatabaseTestCase {

    protected function getDataSet() {
        $xmlFileArray = array(
            TEST_ROOT . '/resources/update-fixture.xml',
        );
        return $this->createCompositeMySQLXMLDataSet($xmlFileArray);
    }

    public function testUpdate() {
        $userModel = new UserModel();

        //更新密码和邮箱
        $newData = array(
            'password' => md5('mypassword'),
            'email' => 'hanmeimei@gmail.com',
        );

        $conditions = array('uid' => 3);

        $rowCountAffected = $userModel->where($conditions)->save($newData);

        $this->assertEquals(1, $rowCountAffected);

        $user = $userModel->where($conditions)->find();

        $this->assertEquals(md5('mypassword'), $user['password']);
    }

}
