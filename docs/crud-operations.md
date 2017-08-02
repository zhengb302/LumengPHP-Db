## CRUD操作

1. [插入](#插入)
    1. [单个插入](#单个插入insert)
    2. [批量插入](#批量插入insertall)
2. [查询](#查询)
    1. [查找一条记录](#查找一条记录)
    2. [查找多条记录](#查找多条记录)
    3. [字段限制](#字段限制)
3. [更新](#更新)
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

查找用户名为*zhangsan*的用户：
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

#### 字段限制(select)

上面的例子未限制要返回的字段，会返回所有字段，现在只想要获得用户名、昵称和年龄三个字段：
```php
//字段列表，跟平时直接写SQL时一样
$fields = 'username,nickname,age';

//使用select方法限制字段
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

增加一个字段的值：
```php
$userModel = new UserModel();

//默认增加的值为1
$rowCount = $userModel->where(['username' => 'zhangsan'])->inc('age');

//自定义增加的值
$rowCount = $userModel->where(['username' => 'zhangsan'])->inc('age', 5);
```

减少一个字段的值：
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