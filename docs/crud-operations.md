# CRUD操作

## 插入数据

代码示例：
```php
$data = [
    'username' => 'zhangsan',
    'nickname' => '张三',
    'age' => 28,
    'sex' => 0,
    'add_time' => time(),
];
$userModel = new Model('User');
$newUserId = userModel->add($data);
```
