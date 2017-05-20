## 查询语言

1. [简单查询](#简单查询)
2. [复合查询](#复合查询)

### 简单查询

注意：所有的查询操作符都是小写格式

#### 等于

```php
$userModel = new Model('User');

//SQL：SELECT * FROM user WHERE username = 'zhangsan'
$conditions = [
    'username' => 'zhangsan',
];
$userData = $userModel->where($conditions)->find();
```

#### 不等于：neq

```php
$userModel = new Model('User');

//SQL：SELECT * FROM user WHERE is_deleted != 1
$conditions = [
    'is_deleted' => ['neq', 1],
];
$userData = $userModel->where($conditions)->find();
```

#### 大于：gt，大于或等于：gte

```php
$userModel = new Model('User');

//SQL：SELECT * FROM user WHERE age > 18
$conditions = [
    'age' => ['gt', 18],
];
$userData = $userModel->where($conditions)->find();

//SQL：SELECT * FROM user WHERE age >= 18
$conditions = [
    'age' => ['gte', 18],
];
$userData = $userModel->where($conditions)->find();
```

#### 小于：lt，小于或等于：lte

```php
$userModel = new Model('User');

//SQL：SELECT * FROM user WHERE age < 18
$conditions = [
    'age' => ['lt', 18],
];
$userData = $userModel->where($conditions)->find();

//SQL：SELECT * FROM user WHERE age <= 18
$conditions = [
    'age' => ['lte', 18],
];
$userData = $userModel->where($conditions)->find();
```

#### in、not in

```php
$userModel = new Model('User');

//SQL：SELECT * FROM user WHERE user_id IN (2, 3, 8)
$conditions = [
    'user_id' => ['in', [2, 3, 8]],
];
$userData = $userModel->where($conditions)->find();

//SQL：SELECT * FROM user WHERE user_id NOT IN (2, 3, 8)
$conditions = [
    'user_id' => ['not in', [2, 3, 8]],
];
$userData = $userModel->where($conditions)->find();
```

#### between、not between

```php
$userModel = new Model('User');

//SQL：SELECT * FROM user WHERE age BETWEEN 18 AND 25
$conditions = [
    'age' => ['between', [18, 25]],
];
$userData = $userModel->where($conditions)->find();

//SQL：SELECT * FROM user WHERE age NOT BETWEEN 18 AND 25
$conditions = [
    'age' => ['not between', [18, 25]],
];
$userData = $userModel->where($conditions)->find();
```

#### like、not like

```php
$userModel = new Model('User');

//SQL：SELECT * FROM user WHERE nickname LIKE '%耐克%'
$conditions = [
    'nickname' => ['like', '%耐克%'],
];
$userData = $userModel->where($conditions)->find();

//SQL：SELECT * FROM user WHERE nickname NOT LIKE '%耐克%'
$conditions = [
    'nickname' => ['not like', '%耐克%'],
];
$userData = $userModel->where($conditions)->find();
```

#### exists、not exists

```php
$userModel = new Model('User');

//SQL：SELECT * FROM user u WHERE EXISTS (SELECT * FROM comment c WHERE c.user_id = u.user_id)
$conditions = [
    '_string' => 'EXISTS (SELECT * FROM comment c WHERE c.user_id = u.user_id)',
];
$userData = $userModel->alias('u')->where($conditions)->find();

//SQL：SELECT * FROM user u WHERE NOT EXISTS (SELECT * FROM comment c WHERE c.user_id = u.user_id)
$conditions = [
    '_string' => 'NOT EXISTS (SELECT * FROM comment c WHERE c.user_id = u.user_id)',
];
$userData = $userModel->alias('u')->where($conditions)->find();
```

### 复合查询

#### 普通AND查询

```php
$userModel = new Model('User');

//SQL：SELECT * FROM user WHERE age > 18 AND sex = 1
$conditions = [
    'age' => ['gt', 18],
    'sex' => 1,
];
$userData = $userModel->where($conditions)->find();
```

#### OR查询

如果不提供逻辑连接词，则默认是AND查询。
若要进行OR查询，则需要使用_**logic**操作来修改逻辑连接词为**or**。

```php
$userModel = new Model('User');

//SQL：SELECT * FROM user WHERE age > 18 OR sex = 1
$conditions = [
    'age' => ['gt', 18],
    'sex' => 1,
    '_logic' => 'or',
];
$userData = $userModel->where($conditions)->find();
```

#### 同一个字段出现多次

可以使用#**number**这样的形式为字段编号，这样同一个字段就可以出现多次。
按约定，**number**从0开始。

```php
$userModel = new Model('User');

//SQL：SELECT * FROM user WHERE age >= 18 AND age <= 25
$conditions = [
    'age#0' => ['gte', 18],
    'age#1' => ['lte', 25],
];
$userData = $userModel->where($conditions)->find();
```

#### 子条件

```php
$userModel = new Model('User');

//SQL：SELECT * FROM user WHERE sex = 1 AND (age < 18 OR age > 25)
$conditions = [
    'sex' => 1,
    '_sub' => [
        'age#0' => ['lt', 18],
        'age#1' => ['gt', 25],
        '_logic' => 'or',
    ],
];
$userData = $userModel->where($conditions)->find();
```

#### 多个子条件

```php
$userModel = new Model('User');

//SQL：SELECT * FROM user WHERE sex = 1 
//        AND (age < 18 OR age > 25) AND (nickname LIKE '张%' OR nickname LIKE '李%')
$conditions = [
    'sex' => 1,
    '_sub#0' => [
        'age#0' => ['lt', 18],
        'age#1' => ['gt', 25],
        '_logic' => 'or',
    ],
    '_sub#1' => [
        'nickname#0' => ['like', '张%'],
        'nickname#1' => ['like', '李%'],
        '_logic' => 'or',
    ],
];
$userData = $userModel->where($conditions)->find();
```

#### 原生SQL条件语句：_string

exists、not exists就是通过_**string**实现的

```php
$userModel = new Model('User');

//SQL：SELECT * FROM user WHERE sex = 1 AND (age < 18 OR age > 25)
$conditions = [
    'sex' => 1,
    '_string' => 'age < 18 OR age > 25',
];
$userData = $userModel->where($conditions)->find();
```

#### 多个_string

```php
$userModel = new Model('User');

//SQL：SELECT * FROM user WHERE sex = 1 
//        AND (age < 18 OR age > 25) AND (nickname LIKE '张%' OR nickname LIKE '李%')
$conditions = [
    'sex' => 1,
    '_string#0' => 'age < 18 OR age > 25',
    '_string#1' => "nickname LIKE '张%' OR nickname LIKE '李%'",
];
$userData = $userModel->where($conditions)->find();
```
