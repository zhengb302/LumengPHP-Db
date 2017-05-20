## 查询语言

### 简单查询

```php
$userModel = new Model('User');

//SQL：SELECT * FROM user WHERE username = 'zhangsan'
$conditions = [
    'username' => 'zhangsan',
];
$userData = $userModel->where($conditions)->find();
```

### 不等于：neq

```php
$userModel = new Model('User');

//SQL：SELECT * FROM user WHERE is_deleted != 1
$conditions = [
    'is_deleted' => ['neq', 1],
];
$userData = $userModel->where($conditions)->find();
```

### 大于：gt，大于或等于：gte
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

### 小于：lt，小于或等于：lte
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

