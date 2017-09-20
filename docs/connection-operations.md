## 连接操作

### 获取`连接管理器`

示例：
```php
$connManager = ConnectionManager::getInstance();
```

### 获取连接

#### 获取默认连接

通过`getDefaultConnection`方法获取默认连接：
```php
$conn = $connManager->getDefaultConnection();
```

通过不带参数的`getConnection`方法获取默认连接：
```php
$conn = $connManager->getConnection();
```

#### 根据`连接名称`获取连接

示例：
```php
$connName = 'bbsLog';
$conn = $connManager->getConnection($connName);
```

#### 从`Model`中获取连接

每个`Model`都有一个数据库连接，通过`Model`的`getConnection`方法，可以获取其所属的数据库连接。

示例：
```php
$conn = $userModel->getConnection();
```