## 主从切换

对于配置了`主从模式`的架构，在某些情况下需要及时获知数据的变动，然而因为主从服务器之间可能存在一定的延迟，所以此时需要只在主服务器上进行读取操作。
`LumengPHP-Db`提供了两个方法用于在进行读取操作的时候在主从服务器之间切换。

> 注意：无论何种情况，写操作一直都是在主服务器上进行的。

> 注意：下文提到的操作都是在`连接`上执行的，而不是`Model`上。

### 禁用从服务器(disableSlaves)

在连接上调用`disableSlaves()`方法会导致该连接上的所有`读`操作都在主服务器上进行，直到调用了`enableSlaves()`方法为止。

### 启用从服务器(enableSlaves)

执行`enableSlaves()`方法会恢复之前的读写分离模式。

> `disableSlaves()`方法一般和`enableSlaves()'方法一起使用。

示例：
```php
//...进行某些数据库操作，此时读取操作都在 从 服务器上进行...

//禁用从服务器
$conn->disableSlaves();

//...进行某些数据库操作，此时读取操作都在 主 服务器上进行...

//启用从服务器
$conn->enableSlaves();

//...进行某些数据库操作，此时读取操作又都在 从 服务器上进行...
```