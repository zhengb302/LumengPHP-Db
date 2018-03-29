<?php

namespace tests\TestCases;

use tests\Model\UserModel;
use tests\Model\PostModel;

/**
 * SQL查询连接测试
 *
 * @author Lumeng <zhengb302@163.com>
 */
class JoinTest extends BaseDatabaseTestCase {

    protected function getDataSet() {
        $xmlFileArray = array(
            TEST_ROOT . '/resources/join-fixture.xml',
        );
        return $this->createCompositeMySQLXMLDataSet($xmlFileArray);
    }

    public function testInnerJoin() {
        $postModel = new PostModel();

        //找出李雷的所有发帖
        $fields = 'p.title,p.content,u.nickname';
        $posts = $postModel->alias('p')->select($fields)
                ->join('bbs_user', 'u', 'u.uid = p.uid')
                ->where(array('p.uid' => 2))
                ->findAll();

        $this->assertCount(3, $posts);
        $this->assertEquals('李雷', $posts[0]['nickname']);
    }

    public function testLeftJoin() {
        $userModel = new UserModel();

        //找出韩梅梅、小明和张三的发帖
        $fields = 'u.uid,u.nickname,p.title,p.content';
        $posts = $userModel->alias('u')->select($fields)
                ->leftJoin('bbs_post', 'p', 'p.uid = u.uid')
                ->where(array('u.uid' => ['in', [3, 1, 4]]))
                ->orderBy('p.add_time ASC')
                ->findAll();

        $expectedResult = array(
            //张三未发帖子，左连接查到的记录为空，post表对应的所有字段皆为null，
            //所以用post表的add_time按正序排序，会排在前面
            array(
                'uid' => '4',
                'nickname' => '张三',
                'title' => null,
                'content' => null,
            ),
            //以下都是有发帖的用户发的帖子，排序正常
            array(
                'uid' => '3',
                'nickname' => '韩梅梅',
                'title' => '嗨，李雷',
                'content' => '呵呵',
            ),
            array(
                'uid' => '3',
                'nickname' => '韩梅梅',
                'title' => '嗨，李雷',
                'content' => '睡了',
            ),
            array(
                'uid' => '1',
                'nickname' => '小明',
                'title' => '嘿嘿',
                'content' => '这真的是个悲伤的故事',
            ),
        );

        $this->assertEquals($expectedResult, $posts);
    }

}
