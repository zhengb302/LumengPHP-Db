## CRUD操作

1. [插入](#插入)
    1. [单个插入(insert)](#单个插入insert)
    2. [批量插入(insertAll)](#批量插入insertall)
2. [查询](#查询)
    1. [查找一条记录(findOne)](#查找一条记录findone)
    2. [查找多条记录(findAll)](#查找多条记录findall)
    3. [选择字段(select)](#选择字段select)
3. [更新](#更新)
    1. [应用表达式(exp)](#应用表达式exp)
    2. [增加一个字段的值(inc)](#增加一个字段的值inc)
    3. [减少一个字段的值(dec)](#减少一个字段的值dec)
4. [删除](#删除)

### 插入

#### 单个插入(insert)

插入一个新用户：
```php
$data = [
    'username' => 'zhangsan',
    'password' => '123456',
    'email' => 'zhangsan@foo.com',
    'nickname' => '张三',
    'age' => 28,
    'sex' => 0,
    'is_deleted' => 0,
    'add_time' => time(),
];

$userModel = new UserModel();
$newUserId = $userModel->insert($data);
```

#### 批量插入(insertAll)

批量插入用户：
```php
$users = [
    [
        'username' => 'zhangsan',
        'password' => '123456',
        'email' => 'zhangsan@foo.com',
        'nickname' => '张三',
        'age' => 28,
        'sex' => 0,
        'is_deleted' => 0,
        'add_time' => time(),
    ],
    [
        'username' => 'lisi',
        'password' => '123456',
        'email' => 'lisi@foo.com',
        'nickname' => '李四',
        'age' => 29,
        'sex' => 0,
        'is_deleted' => 0,
        'add_time' => time(),
    ],
];

$userModel = new UserModel();
$rowCount = $userModel->insertAll($users);
```

### 查询

查询返回的每一条记录都是关联数组。

#### 查找一条记录(findOne)

查找用户名为“zhangsan”的用户：
```php
$userModel = new UserModel();
$userData = $userModel->where(['username' => 'zhangsan'])->findOne();
```

#### 查找多条记录(findAll)

查找所有女生(sex等于1)：
```php
$userModel = new UserModel();
$girls = $userModel->where(['sex' => 1])->findAll();
```

#### 选择字段(select)

上面的例子未选择要返回的字段(会返回所有字段)，现在只想要获得用户名、昵称和年龄三个字段：
```php
//字段列表，跟平时直接写SQL时一样
$fields = 'username,nickname,age';

//使用select方法选择字段
$userModel = new UserModel();
$userData = $userModel->select($fields)->where(['username' => 'zhangsan'])->findOne();
```

字段别名：
```php
$fields = 'username AS 用户名,nickname 昵称,age';
```

下面的设置也会返回所有字段：
```php
$fields = '*';
```

select方法也接受数组作为参数：
```php
$fields = ['username', 'nickname', 'age', 'sex AS gender'];
```

#### 结果去重(distinct)

调用`distinct`方法会在SQL语句的查询字段前面插入`DISTINCT`关键字，使得返回的结果集不会存在重复的记录。

#### 表别名(alias)

示例：
```php
$fields = 'u.username,u.nickname,u.age';
$userModel = new UserModel();
$userData = $userModel->alias('u')->select($fields)->where(['u.username' => 'zhangsan'])->findOne();
```

#### 连接查询

内连接(`join`方法)：
```php
//找出李雷的所有发帖
$postModel = new PostModel();
$posts = $postModel->alias('p')->select('p.title,p.content,u.nickname')
                               ->join('User', 'u', 'u.uid = p.uid')
                               ->where(['u.username' => 'lilei'])
                               ->findAll();
```

左外连接(`leftJoin`方法)、右外连接同理(`rightJoin`方法)，调用方式与内连接(`join`方法)一致。

#### groupBy、having

查找出发帖次数超过5次的用户的发帖数：
```php
$postModel = new PostModel();
$posts = $postModel->select('uid, COUNT(id)')
                   ->groupBy('uid')
                   ->having('COUNT(id) >= 5')
                   ->findAll();
```

#### 排序(orderBy)

找出李雷的所有发帖，越新的排在越前面：
```php
$postModel = new PostModel();
$posts = $postModel->alias('p')->select('p.title,p.content,u.nickname')
                               ->join('User', 'u', 'u.uid = p.uid')
                               ->where(['u.username' => 'lilei'])
                               ->orderBy('p.add_time DESC')
                               ->findAll();
```

`orderBy`方法也接受一个关联数组作为参数：
```php
//先按用户ID升序排序，再按发帖时间逆序排序
$orderBy = [
    'uid' => 'ASC',
    'add_time' => 'DESC',
];
$postModel = new PostModel();
$posts = $postModel->select('uid,title,content')
                   ->orderBy($orderBy)
                   ->findAll();
```

#### 分页(paging)

返回第50页的帖子，每页20条：
```php
$pageNum = 50;
$pageSize = 20;
$postModel = new PostModel();
$posts = $postModel->select('id,title,content')
                   ->paging($pageNum, $pageSize)
                   ->findAll();
```

> 注意：分页号从`1`开始，而不是`0`

### 更新

示例：
```php
$newData = [
    'nickname' => '尼古拉斯*张三',
    'age' => 19,
];
$userModel = new UserModel();
$rowCount = $userModel->where(['username' => 'zhangsan'])->update($newData);
```

#### 应用表达式(exp)

对要更新的字段值应用表达式：
```php
$newData = [
    'age' => ['exp', 'age + 1'],
];
$userModel = new UserModel();
$rowCount = $userModel->where(['username' => 'zhangsan'])->update($newData);
```

对应的SQL语句：
```sql
UPDATE user SET age = age + 1 WHERE username = 'zhangsan'
```

#### 增加一个字段的值(inc)

```php
$userModel = new UserModel();

//默认增加的值为1
$rowCount = $userModel->where(['username' => 'zhangsan'])->inc('age');

//自定义增加的值
$rowCount = $userModel->where(['username' => 'zhangsan'])->inc('age', 5);
```

#### 减少一个字段的值(dec)
```php
$userModel = new UserModel();

//默认减少的值为1
$rowCount = $userModel->where(['username' => 'zhangsan'])->dec('age');

//自定义减少的值
$rowCount = $userModel->where(['username' => 'zhangsan'])->dec('age', 5);
```

### 删除

示例：
```php
$userModel = new UserModel();
$rowCount = $userModel->where(['username' => 'zhangsan'])->delete();
```