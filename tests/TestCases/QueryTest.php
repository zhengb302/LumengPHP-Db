<?php

namespace tests\TestCases;

use tests\Model\UserModel;

/**
 * 查询测试
 *
 * @author Lumeng <zhengb302@163.com>
 */
class QueryTest extends BaseDatabaseTestCase {

    protected function getDataSet() {
        $xmlFileArray = array(
            TEST_ROOT . '/resources/query-fixture.xml',
        );
        return $this->createCompositeMySQLXMLDataSet($xmlFileArray);
    }

    public function testQuery() {
        $conditions = array(
            'username' => 'xiaoming',
        );

        $userModel = new UserModel();
        $user = $userModel->field('*')->where($conditions)->find();
        $this->assertNotNull($user);
        $this->assertEquals('xiaoming', $user['username']);
    }

    public function testQueryTwice() {
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

    /**
     * 测试不设置字段的查询
     */
    public function testQueryWithoutSettingFields() {
        $conditions = array(
            'username' => 'xiaoming',
        );

        $userModel = new UserModel();

        //without setting fields
        $user = $userModel->where($conditions)->find();
        $this->assertNotFalse($user);

        //query again
        $user2 = $userModel->where($conditions)->find();
        $this->assertNotFalse($user2);
    }

    public function testCount() {
        $userModel = new UserModel();
        $result = $userModel->count();
        $this->assertEquals(5, $result);
    }

    public function testMax() {
        $userModel = new UserModel();
        $result = $userModel->max('uid');
        $this->assertEquals(5, $result);
    }

    public function testMin() {
        $userModel = new UserModel();
        $result = $userModel->min('uid');
        $this->assertEquals(1, $result);
    }

    public function testAvg() {
        $userModel = new UserModel();
        $result = $userModel->avg('uid');
        $this->assertEquals(3, $result);
    }

    public function testSum() {
        $userModel = new UserModel();
        $result = $userModel->sum('uid');
        $this->assertEquals(15, $result);
    }

    public function testAlias() {
        $userModel = new UserModel();
        $result = $userModel->field('u.uid,u.nickname')->alias('u')
                        ->where(array('uid' => 2))->find();
        $this->assertEquals('李雷', $result['nickname']);
    }

    public function testSort() {
        $userModel = new UserModel();
        $result = $userModel->orderBy('add_time DESC')->find();
        $this->assertEquals('李四', $result['nickname']);
    }

    public function testLimit() {
        $userModel = new UserModel();
        $rows = $userModel->limit(3)->select();
        $this->assertEquals(3, count($rows));
        $this->assertEquals('韩梅梅', $rows[2]['nickname']);
    }

    public function testPaging() {
        $userModel = new UserModel();
        $rows = $userModel->paging(2, 2)->select();
        $this->assertEquals(2, count($rows));
        $this->assertEquals('张三', $rows[1]['nickname']);
    }

}
