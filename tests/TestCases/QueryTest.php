<?php

namespace tests\TestCases;

use tests\Model\User;

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

        $userModel = new User();
        $user = $userModel->select('*')->where($conditions)->findOne();
        $this->assertNotNull($user);
        $this->assertEquals('xiaoming', $user['username']);
    }

    public function testQueryTwice() {
        $conditions = array(
            'username' => 'xiaoming',
        );

        $userModel = new User();
        $user = $userModel->select('*')->where($conditions)->findOne();
        $this->assertNotNull($user);
        $this->assertEquals('xiaoming', $user['username']);

        $user2 = $userModel->select('*')->where($conditions)->findOne();
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

        $userModel = new User();

        //without setting fields
        $user = $userModel->where($conditions)->findOne();
        $this->assertNotFalse($user);

        //query again
        $user2 = $userModel->where($conditions)->findOne();
        $this->assertNotFalse($user2);
    }

    public function testCount() {
        $userModel = new User();
        $result = $userModel->count();
        $this->assertEquals(5, $result);
    }

    public function testMax() {
        $userModel = new User();
        $result = $userModel->max('uid');
        $this->assertEquals(5, $result);
    }

    public function testMin() {
        $userModel = new User();
        $result = $userModel->min('uid');
        $this->assertEquals(1, $result);
    }

    public function testAvg() {
        $userModel = new User();
        $result = $userModel->avg('uid');
        $this->assertEquals(3, $result);
    }

    public function testSum() {
        $userModel = new User();
        $result = $userModel->sum('uid');
        $this->assertEquals(15, $result);
    }

    public function testAlias() {
        $userModel = new User();
        $result = $userModel->select('u.uid,u.nickname')->alias('u')
                        ->where(array('uid' => 2))->find();
        $this->assertEquals('李雷', $result['nickname']);
    }

    public function testSort() {
        $userModel = new User();
        $result = $userModel->orderBy('add_time DESC')->findOne();
        $this->assertEquals('李四', $result['nickname']);
    }

    public function testLimit() {
        $userModel = new User();
        $rows = $userModel->limit(3)->findAll();
        $this->assertEquals(3, count($rows));
        $this->assertEquals('韩梅梅', $rows[2]['nickname']);
    }

    public function testPaging() {
        $userModel = new User();
        $rows = $userModel->paging(2, 2)->findAll();
        $this->assertEquals(2, count($rows));
        $this->assertEquals('张三', $rows[1]['nickname']);
    }

    /**
     * 测试SQL语句执行出错的情况
     */
    public function testQueryWithWrongSql() {
        $userModel = new User();

        //找出韩梅梅，然而并没有"qq_number"这个字段，会导致SQL执行出错
        $hanmeimei = $userModel->select('uid,username,qq_number')
                ->where(array('uid' => 3))
                ->findAll();

        //SQL执行出错，会返回false
        $this->assertFalse($hanmeimei);
    }

    /**
     * 测试没有找到数据的情况(注意：这种情况下SQL执行并没有出错)
     */
    public function testQueryWithNoResult() {
        $userModel = new User();

        //找出username为"linda"的这个用户，然而并没有这个用户
        $linda = $userModel->where(array('username' => 'linda'))->findOne();

        //只是没找到数据，并不是SQL执行出错，所以不是返回false
        $this->assertNotFalse($linda);

        //当前查询没有找到数据，会返回null
        $this->assertNull($linda);
    }

}
