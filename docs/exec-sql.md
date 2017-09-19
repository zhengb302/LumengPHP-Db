## 执行SQL语句

通常`CRUD`操作大部分在`Model`里完成，然而有一些情况使用原始的SQL语句会更方便，像后台计算、统计、复杂的查询等场景。
使用原始的SQL语句需要特别注意`SQL注入`问题，对于不确定的用户输入，最好避免直接把用户输入拼接到SQL语句中。
下文介绍的这些操作都支持`预编译语句`绑定参数的方式，对于用户输入，应使用`参数绑定`而不是直接拼接到SQL语句中。

### 执行查询语句

#### 返回单条记录(query)

`query`方法会返回查询结果中的第一条记录（如果有），如果记录不存在，则返回`null`。

查询用户名为“zhangsan”的用户：
```php
$username = 'zhangsan';
$sql = "SELECT * FROM bbs_user WHERE username = '{$username}'";
$user = $conn->query($sql);
```

上面这个例子直接在程序里把用户名写死了，并没有什么大碍，然而如果用户名来自用户输入，则应该使用`参数绑定`的方式来避免`SQL注入`问题。

查询某个用户，用户名来自用户输入：
```php
$username = $_GET['username'];
$sql = "SELECT * FROM bbs_user WHERE username = :username";
$parameters = [
    ':username' => $username,
];
$user = $conn->query($sql, $parameters);
```

使用`参数绑定`的方式不仅能避免`SQL注入`问题，而且能使程序更健壮。例如，假如此时用户名里包含单引号`'`，如果直接拼接SQL语句，就会得到一个错误的SQL。

查询用户名为`zhang'san`的用户：
```php
$username = "zhang'san";
$sql = "SELECT * FROM bbs_user WHERE username = '{$username}'";
$user = $conn->query($sql);
```

此时`query`方法返回的结果是布尔值`false`，因为执行的是一个语法错误的SQL语句：
```sql
SELECT * FROM bbs_user WHERE username = 'zhang'san'
```

注意结尾的三个单引号。而如果使用`参数绑定`的方式，参数中有多少个单引号都不会导致SQL语句错误。

#### 返回多条记录(queryAll)

`queryAll`方法会返回查询到的所有记录，如果记录不存在，也返回`null`。

查询所有未删除的女性用户：
```php
$sql = "SELECT * FROM bbs_user WHERE sex = 1 AND is_deleted = 0";
$users = $conn->queryAll($sql);
```

### 执行插入、更新或删除语句(execute)

`execute`方法用来执行`插入`、`更新`或`删除`语句，并返回相应的执行结果。

更新用户名为“zhangsan”的用户的年龄：
```php
$sql = 'UPDATE bbs_user SET age = :age WHERE username = :username';
$parameters = [
    ':username' => 'zhangsan',
    ':age' => 30,
];
$affectedRowCount = $conn->execute($sql, $parameters);
```

#### 返回最新插入的记录的ID(lastInsertId)

如果使用`execute`方法插入了一条记录，那么可以使用`lastInsertId`方法获取最新插入记录的自增ID。

新增一条帖子并获取这个帖子的ID：
```php
$sql = 'INSERT INTO bbs_post(uid, title, content, add_time) VALUES(:uid, :title, :content, :add_time)';
$parameters = [
    ':uid' => 8,
    ':title' => '震惊！iPhone X只要9块9！',
    ':content' => '只要9块9，iPhone X带回家！9块9，买不了吃亏，买不了上当！你值的拥有！',
    ':add_time' => time(),
];
$conn->execute($sql, $parameters);
$newPostId = $conn->lastInsertId();
```