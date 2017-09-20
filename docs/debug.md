## 调试

返回最后一条执行的SQL：
```php
$lastSql = $conn->getLastSql();
```

如果配置了日志组件(Logger)，在SQL执行出错的时候还会记录错误消息及SQL语句。