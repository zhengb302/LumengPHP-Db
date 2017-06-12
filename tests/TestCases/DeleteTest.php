<?php

namespace tests\TestCases;

use tests\Model\UserModel;

/**
 * 删除数据测试
 *
 * @author Lumeng <zhengb302@163.com>
 */
class DeleteTest extends BaseDatabaseTestCase {

    protected function getDataSet() {
        $xmlFileArray = array(
            TEST_ROOT . '/resources/delete-fixture.xml',
        );
        return $this->createCompositeMySQLXMLDataSet($xmlFileArray);
    }

    public function testDelete() {
        $userModel = new UserModel();

        //把李雷删掉
        $conditions = array('uid' => 2);

        $rowCountAffected = $userModel->where($conditions)->delete();
        $this->assertEquals(1, $rowCountAffected);

        $user = $userModel->where($conditions)->findOne();
        $this->assertNull($user);
    }

    public function testDeleteNothing() {
        $userModel = new UserModel();

        //删除一个不存在的用户
        $conditions = array('uid' => 250);
        $rowCountAffected = $userModel->where($conditions)->delete();

        //没有数据被删除，受影响的行数为0
        $this->assertEquals(0, $rowCountAffected);
    }

    /**
     * 测试不带条件的数据删除<br />
     * 不带条件的数据删除，会导致抛出异常
     * @expectedException \LumengPHP\Db\Exception\ForbiddenOperationException
     */
    public function testDeleteWithoutAnyConditions() {
        $userModel = new UserModel();
        $userModel->delete();
    }

}
