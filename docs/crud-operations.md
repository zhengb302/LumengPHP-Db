## CRUD操作

1. [插入](#插入)
2. [查询](#查询)
  2.1 [查找一条记录](#查找一条记录)
  2.2 [查找多条记录](#查找多条记录)


### 插入

插入一个新用户：
```php
$data = [
    'username' => 'zhangsan',
    'nickname' => '张三',
    'age' => 28,
    'sex' => 0,
    'add_time' => time(),
];

$userModel = new Model('User');
$newUserId = $userModel->add($data);
```
### 查询

查询返回的每一条记录都是关联数组。

#### 查找一条记录

查找用户名为*zhangsan*的用户：
```php
$userModel = new Model('User');
$userData = $userModel->where(['username' => 'zhangsan'])->find();
```

#### 查找多条记录

查找所有女生(sex等于1)：
```php
$userModel = new Model('User');
$girls = $userModel->where(['sex' => 1])->select();
```

#### 字段限制

上面的例子未限制要返回的字段，会返回所有字段，现在只想要获得用户名、昵称和年龄三个字段：
```php
$userModel = new Model('User');

//字段列表，跟平时直接写SQL时一样
$fields = 'username,nickname,age';

$userData = $userModel->field($fields)->where(['username' => 'zhangsan'])->find();
```

字段别名：
```php
$fields = 'username AS 用户名,nickname 昵称,age';
```

下面的设置也会返回所有字段：
```php
$fields = '*';
```