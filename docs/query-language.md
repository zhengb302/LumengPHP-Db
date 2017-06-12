## 查询过滤语言

1. [简单查询](#简单查询)
2. [复合查询](#复合查询)

### 简单查询

注意：所有的简单查询操作符都是小写格式

#### 等于

```php
$userModel = new UserModel();

//SQL：SELECT * FROM bbs_user WHERE username = 'zhangsan'
$conditions = [
    'username' => 'zhangsan',
];
$userData = $userModel->where($conditions)->findOne();
```

#### 不等于：neq

```php
$userModel = new UserModel();

//SQL：SELECT * FROM bbs_user WHERE is_deleted != 1
$conditions = [
    'is_deleted' => ['neq', 1],
];
$userData = $userModel->where($conditions)->findOne();
```

#### 大于：gt，大于或等于：gte

```php
$userModel = new UserModel();

//SQL：SELECT * FROM bbs_user WHERE age > 18
$conditions = [
    'age' => ['gt', 18],
];
$userData = $userModel->where($conditions)->findOne();

//SQL：SELECT * FROM bbs_user WHERE age >= 18
$conditions = [
    'age' => ['gte', 18],
];
$userData = $userModel->where($conditions)->findOne();
```

#### 小于：lt，小于或等于：lte

```php
$userModel = new UserModel();

//SQL：SELECT * FROM bbs_user WHERE age < 18
$conditions = [
    'age' => ['lt', 18],
];
$userData = $userModel->where($conditions)->findOne();

//SQL：SELECT * FROM bbs_user WHERE age <= 18
$conditions = [
    'age' => ['lte', 18],
];
$userData = $userModel->where($conditions)->findOne();
```

#### in、not in

```php
$userModel = new UserModel();

//SQL：SELECT * FROM bbs_user WHERE uid IN (2, 3, 8)
$conditions = [
    'uid' => ['in', [2, 3, 8]],
];
$userData = $userModel->where($conditions)->findOne();

//SQL：SELECT * FROM bbs_user WHERE uid NOT IN (2, 3, 8)
$conditions = [
    'uid' => ['not in', [2, 3, 8]],
];
$userData = $userModel->where($conditions)->findOne();
```

#### between、not between

```php
$userModel = new UserModel();

//SQL：SELECT * FROM bbs_user WHERE age BETWEEN 18 AND 25
$conditions = [
    'age' => ['between', 18, 25],
];
$userData = $userModel->where($conditions)->findOne();

//SQL：SELECT * FROM bbs_user WHERE age NOT BETWEEN 18 AND 25
$conditions = [
    'age' => ['not between', 18, 25],
];
$userData = $userModel->where($conditions)->findOne();
```

#### like、not like

```php
$userModel = new UserModel();

//SQL：SELECT * FROM bbs_user WHERE nickname LIKE '%耐克%'
$conditions = [
    'nickname' => ['like', '%耐克%'],
];
$userData = $userModel->where($conditions)->findOne();

//SQL：SELECT * FROM bbs_user WHERE nickname NOT LIKE '%耐克%'
$conditions = [
    'nickname' => ['not like', '%耐克%'],
];
$userData = $userModel->where($conditions)->findOne();
```

#### exists、not exists

```php
$userModel = new UserModel();

//SQL：SELECT * FROM bbs_user u WHERE EXISTS (SELECT * FROM bbs_post p WHERE p.uid = u.uid)
$conditions = [
    '_string' => 'EXISTS (SELECT * FROM bbs_post p WHERE p.uid = u.uid)',
];
$userData = $userModel->alias('u')->where($conditions)->findOne();

//SQL：SELECT * FROM bbs_user u WHERE NOT EXISTS (SELECT * FROM bbs_post p WHERE p.uid = u.uid)
$conditions = [
    '_string' => 'NOT EXISTS (SELECT * FROM bbs_post p WHERE p.uid = u.uid)',
];
$userData = $userModel->alias('u')->where($conditions)->findOne();
```

### 复合查询

复合查询操作符：
* _logic    设置逻辑连接词。逻辑连接词有：and、or。如果不提供逻辑操作符，则默认的连接词是**and**。逻辑连接词必须为小写。
* _sub      添加子条件
* _or       用**or**逻辑连接词连接多个子条件
* _and      用**and**逻辑连接词连接多个子条件
* _string   添加原生SQL条件

#### 普通复合查询

格式：
```
[ 
    <field1> => <value1>,
    <field2> => <value2>,
    ...
]
```

示例：
```php
$conditions = [
    'age' => ['gt', 18],
    'sex' => 1,
];
$userData = $userModel->where($conditions)->findOne();
```

SQL：
```sql
SELECT * FROM bbs_user WHERE (age > 18 AND sex = 1)
```

#### _logic操作符

如果不提供逻辑连接词，则默认是AND查询。
若要进行OR查询，则需要使用_**logic**操作来修改逻辑连接词为**or**。

格式：
```
[ 
    <field1> => <value1>,
    <field2> => <value2>,
    ...
    '_logic' => 'or',
]
```

示例：
```php
$conditions = [
    'age' => ['gt', 18],
    'sex' => 1,
    '_logic' => 'or',
];
$userData = $userModel->where($conditions)->findOne();
```

SQL：
```sql
SELECT * FROM bbs_user WHERE (age > 18 OR sex = 1)
```

#### _sub操作符，子条件

格式：
```
[ 
    '_sub' => [
        <field1> => <value1>,
        <field2> => <value2>,
        ...
    ]
]
```

示例：
```php
$conditions = [
    'is_deleted' => 0,
    '_sub' => [
        'age' => ['gt', 18],
        'sex' => 1,
    ],
];
$userData = $userModel->where($conditions)->findOne();
```

SQL：
```sql
-- 注意里边的圆括号
SELECT * FROM bbs_user WHERE (is_deleted = 0 AND (age > 18 AND sex = 1))
```

以上示例不需要_**sub**操作符也可以轻易实现，所以\_**sub**操作符一般与\_**logic**一起使用，嵌入一个OR子句：
```php
$conditions = [
    'is_deleted' => 0,
    '_sub' => [
        'age' => ['gt', 18],
        'sex' => 1,
        '_logic' => 'or',
    ],
];
$userData = $userModel->where($conditions)->findOne();
```

SQL：
```sql
SELECT * FROM bbs_user WHERE (is_deleted = 0 AND (age > 18 OR sex = 1))
```

#### _or 操作符

格式：
```
[ 
    '_or' => [
        <sub condition 1>,
        <sub condition 2>,
        ...
    ]
]
```

示例：
```php
$conditions = [
    'is_deleted' => 0,
    '_or' => [
        [
            'nickname' => ['like', '张%'],
            'sex' => 0,
        ],
        [
            'nickname' => ['like', '李%'],
            'sex' => 1,
        ],
    ],
];
$userData = $userModel->where($conditions)->findOne();
```

SQL：
```sql
SELECT * FROM bbs_user WHERE (is_deleted = 0 AND ((nickname LIKE '张%' AND sex = 0) OR (nickname LIKE '李%' AND sex = 1)))
```

#### _and操作符

格式：
```
[ 
    '_and' => [
        <sub condition 1>,
        <sub condition 2>,
        ...
    ]
]
```

示例：
```php
$conditions = [
    'is_deleted' => 0,
    '_and' => [
        [
            'nickname' => ['like' ,'张%'],
            'sex' => 0,
            '_logic' => 'or',
        ],
        [
            'nickname' => ['like' ,'李%'],
            'sex' => 1,
            '_logic' => 'or',
        ],
    ],
];
$userData = $userModel->where($conditions)->findOne();
```

SQL：
```sql
SELECT * FROM bbs_user WHERE (is_deleted = 0 AND ((nickname LIKE '张%' OR sex = 0) AND (nickname LIKE '李%' OR sex = 1)))
```

#### 原生SQL条件语句：_string

exists、not exists就是通过_**string**实现的。

格式：
```
[ 
    '_string' => <原生SQL条件语句>,
]

//或者
[ 
    '_string' => [<原生SQL条件语句1>, <原生SQL条件语句2>, ...],
]
```

示例1：
```php
$conditions = [
    'sex' => 1,
    '_string' => 'age < 18 OR age > 25',
];
$userData = $userModel->where($conditions)->findOne();
```

SQL：
```sql
SELECT * FROM bbs_user WHERE (sex = 1 AND (age < 18 OR age > 25))
```

示例2：
```php
$conditions = [
    'sex' => 1,
    '_string' => ['age < 18 OR age > 25', "nickname LIKE '张%' OR nickname LIKE '李%'"],
];
$userData = $userModel->where($conditions)->findOne();
```

SQL：
```sql
SELECT * FROM bbs_user WHERE (sex = 1 AND ((age < 18 OR age > 25) AND (nickname LIKE '张%' OR nickname LIKE '李%')))
```