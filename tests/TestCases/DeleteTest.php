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

        $user = $userModel->where($conditions)->find();

        $this->assertNull($user);
    }

    /**
     * 测试不带条件的数据删除
     * @expectedException \LumengPHP\Db\Exceptions\ForbiddenDatabaseOperationException
     */
    public function testDeleteWithoutAnyConditions() {
        $userModel = new UserModel();
        $userModel->delete();
    }

}
