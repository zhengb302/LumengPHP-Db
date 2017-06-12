<?php

namespace tests\TestCases;

use LumengPHP\Db\ConnectionManager;
use tests\Model\UserModel;
use tests\Model\PostModel;

/**
 * 事务测试
 *
 * @author Lumeng <zhengb302@163.com>
 */
class TransactionTest extends BaseDatabaseTestCase {

    protected function getDataSet() {
        $xmlFileArray = array(
            TEST_ROOT . '/resources/transaction-fixture.xml',
        );
        return $this->createCompositeMySQLXMLDataSet($xmlFileArray);
    }

    public function testCommit() {
        $userModel = new UserModel();
        $postModel = new PostModel();

        $connManager = ConnectionManager::getInstance();
        $conn = $connManager->getConnection();

        $conn->beginTransaction();

        //删除李雷
        $userModel->where(array('username' => 'lilei'))->delete();

        //删除李雷的发帖
        $postModel->where(array('uid' => 2))->delete();

        $conn->commit();

        //现在只剩下三个用户
        $this->assertEquals(3, $userModel->count());
        //李雷已被删除，再查找这个用户，会返回null
        $lilei = $userModel->where(array('username' => 'lilei'))->findOne();
        $this->assertNull($lilei);
        //查找李雷的发帖，也返回null
        $postsOfLilei = $postModel->where(array('uid' => 2))->findAll();
        $this->assertNull($postsOfLilei);
    }

    public function testRollback() {
        $userModel = new UserModel();
        $postModel = new PostModel();

        $connManager = ConnectionManager::getInstance();
        $conn = $connManager->getConnection();

        $conn->beginTransaction();

        //删除李雷
        $userModel->where(array('username' => 'lilei'))->delete();

        //删除李雷的发帖
        $postModel->where(array('uid' => 2))->delete();

        $conn->rollback();

        //因为回滚了事务，现在还是四个用户
        $this->assertEquals(4, $userModel->count());
        //因为回滚了事务，再查找李雷，李雷的用户数据并没有被删除
        $lilei = $userModel->where(array('username' => 'lilei'))->findOne();
        $this->assertNotNull($lilei);
        $this->assertEquals('李雷', $lilei['nickname']);
        //因为回滚了事务，查找李雷的发帖，都还在
        $postsOfLilei = $postModel->where(array('uid' => 2))->findAll();
        $this->assertNotNull($postsOfLilei);
        $this->assertCount(3, $postsOfLilei);
    }

}
