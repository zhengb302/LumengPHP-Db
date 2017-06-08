<?php

namespace tests\TestCases;

use tests\Model\User;
use tests\Model\Post;

/**
 * SQL查询条件测试
 *
 * @author Lumeng <zhengb302@163.com>
 */
class SQLConditionTest extends BaseDatabaseTestCase {

    protected function getDataSet() {
        $xmlFileArray = array(
            TEST_ROOT . '/resources/sql-condition-fixture.xml',
        );
        return $this->createCompositeMySQLXMLDataSet($xmlFileArray);
    }

    public function testBetween() {
        $userModel = new User();

        $conditions = array(
            'uid' => ['between', 2, 5],
        );
        $users = $userModel->where($conditions)->findAll();

        $this->assertCount(3, $users);
    }

    public function testNotBetween() {
        $userModel = new User();

        $conditions = array(
            'uid' => ['not between', 2, 5],
        );
        $users = $userModel->where($conditions)->findAll();

        $this->assertCount(1, $users);
    }

    public function testNotEqual() {
        $postModel = new Post();

        //找出除了李雷之外，其他人发的所有帖子
        $conditions = array(
            'uid' => ['neq', 2],
        );
        $posts = $postModel->where($conditions)->findAll();

        $this->assertCount(3, $posts);
        $this->assertEquals('呵呵', $posts[0]['content']);
    }

    public function testExists() {
        $userModel = new User();

        //找出发过帖子的所有用户
        $condition = [
            '_string' => 'EXISTS (SELECT * FROM bbs_post p WHERE p.uid = u.uid)',
        ];
        $users = $userModel->alias('u')->select('u.uid,u.nickname')
                ->where($condition)
                ->findAll();

        //沉默寡言的张三
        $taciturnUser = array(
            'uid' => '4',
            'nickname' => '张三',
        );

        $this->assertCount(3, $users);
        $this->assertNotContains($taciturnUser, $users);
    }

    public function testNotExists() {
        $userModel = new User();

        //找出尚未发过帖子的所有用户
        $condition = [
            '_string' => 'NOT EXISTS(SELECT * FROM bbs_post p WHERE p.uid = u.uid)',
        ];
        $taciturnUsers = $userModel->alias('u')->select('u.uid,u.nickname')
                ->where($condition)
                ->findAll();

        $this->assertCount(1, $taciturnUsers);
        $this->assertEquals('张三', $taciturnUsers[0]['nickname']);
    }

    public function testGreaterThan() {
        $userModel = new User();

        //找出uid大于2的所有用户
        $conditions = array(
            'uid' => ['gt', 2],
        );
        $users = $userModel->where($conditions)->findAll();

        $this->assertCount(2, $users);
        $this->assertEquals('韩梅梅', $users[0]['nickname']);
    }

    public function testLessEqualThan() {
        $userModel = new User();

        //找出uid小于或等于2的所有用户
        $conditions = array(
            'uid' => ['lte', 2],
        );
        $users = $userModel->where($conditions)->findAll();

        $this->assertCount(2, $users);
        $this->assertEquals('李雷', $users[1]['nickname']);
    }

    public function testIn() {
        $postModel = new Post();

        //找出李雷和韩梅梅发的所有帖子
        $conditions = array(
            'uid' => ['in', [2, 3]],
        );
        $posts = $postModel->where($conditions)->findAll();

        $this->assertCount(5, $posts);
        $this->assertEquals('睡了', $posts[4]['content']);
    }

    public function testLike() {
        $postModel = new Post();

        //找出标题里包含"韩梅梅"的所有帖子
        $conditions = array(
            'title' => ['like', '%韩梅梅%'],
        );
        $posts = $postModel->where($conditions)->findAll();

        $this->assertCount(3, $posts);
        $this->assertEquals('“呵呵”是啥意思？', $posts[2]['content']);
    }

}
