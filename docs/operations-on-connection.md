## 连接上的操作

### 获取`连接管理器`

示例：
```php
$connManager = ConnectionManager::getInstance();
```

### 获取连接

#### 获取默认连接

方法一：
```php
$conn = $connManager->getDefaultConnection();
```

方法二：
```php
$conn = $connManager->getConnection();
```

#### 根据`连接名称`获取连接

示例：
```php
$connName = 'bbsLog';
$conn = $connManager->getConnection($connName);
```

### 获取`表前缀`

```php
$tablePrefix = $conn->getTablePrefix();
```

