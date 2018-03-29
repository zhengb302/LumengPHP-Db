## CRUD操作

1. [插入](#插入)
    - [单个插入(insert)](#单个插入insert)
    - [批量插入(insertAll)](#批量插入insertall)
2. [查询](#查询)
    - [查找一条记录(findOne)](#查找一条记录findone)
    - [查找多条记录(findAll)](#查找多条记录findall)
    - [选择字段(select)](#选择字段select)
    - [查找单个值(findValue)](#查找单个值findvalue)
    - [查找一个列的值(findColumn)](#查找一个列的值findcolumn)
    - [结果去重(distinct)](#结果去重distinct)
    - [表别名(alias)](#表别名alias)
    - [连接查询(join、leftJoin、rightJoin)](#连接查询)
    - [groupBy、having](#groupbyhaving)
    - [排序(orderBy)](#排序orderby)
    - [分页(paging)](#分页paging)
    - [限制返回的结果集(limit)](#限制返回的结果集limit)
    - [聚簇函数(count、max、min、avg、sum)](#聚簇函数)
3. [更新](#更新)
    - [应用表达式(exp)](#应用表达式exp)
    - [增加一个字段的值(inc)](#增加一个字段的值inc)
    - [减少一个字段的值(dec)](#减少一个字段的值dec)
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

#### 查找单个值(findValue)

`findValue`方法用于查找单个值，即：某条记录的某个字段的值。

取得用户“张三”的年龄：
```php
$userModel = new UserModel();
$age = $userModel->where(['username' => 'zhangsan'])->findValue('age');
```

#### 查找一个列的值(findColumn)

`findColumn`用于查找复合条件的结果集的某个列，并以下标数组的形式返回。配合`distinct`方法，还可以返回不重复的值。

获取所有用户的email，并且去重后返回：
```php
$userModel = new UserModel();
$emails = $userModel->distinct()->findColumn('email');
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
$posts = $postModel->alias('p')->select('p.id,u.nickname,up.avatar,p.title,p.content,p.add_time')
                               ->join('bbs_user', 'u', 'u.uid = p.uid')
                               ->join('bbs_user_profile', 'up', 'up.uid = p.uid')
                               ->where(['u.username' => 'lilei'])
                               ->findAll();
```

左外连接(`leftJoin`方法)、右外连接(`rightJoin`方法)同理，调用方式与内连接(`join`方法)一致。

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

返回第5页的帖子，每页20条：
```php
$pageNum = 5;
$pageSize = 20;
$postModel = new PostModel();
$posts = $postModel->select('id,title,content')
                   ->paging($pageNum, $pageSize)
                   ->findAll();
```

> 注意：分页号从`1`开始，而不是`0`。

#### 限制返回的结果集(limit)

返回最新发布的10条帖子：
```php
$postModel = new PostModel();
$posts = $postModel->select('uid,title,content')
                   ->orderBy('add_time DESC')
                   ->limit(10)
                   ->findAll();
```

`limit`方法也支持`limit offset, size`的形式：
```php
$postModel = new PostModel();
$posts = $postModel->select('uid,title,content')
                   ->orderBy('add_time DESC')
                   ->limit('40, 10')
                   ->findAll();
```

> 注意：在同一个查询内同时调用`paging`方法和`limit`方法会导致互相覆盖。

#### 聚簇函数

`count`方法：
```php
//返回未删除用户的数量
$userModel = new UserModel();
$count = $userModel->where(['is_deleted' => 0])->count();
```

`max`方法：
```php
//返回未删除用户的最大年龄
$userModel = new UserModel();
$count = $userModel->where(['is_deleted' => 0])->max('age');
```

`min`方法：
```php
//返回未删除用户的最小年龄
$userModel = new UserModel();
$count = $userModel->where(['is_deleted' => 0])->min('age');
```

`avg`方法：
```php
//返回未删除用户的平均年龄
$userModel = new UserModel();
$count = $userModel->where(['is_deleted' => 0])->avg('age');
```

`sum`方法：
```php
//返回未删除用户的总年龄，这。。。
$userModel = new UserModel();
$count = $userModel->where(['is_deleted' => 0])->sum('age');
```

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