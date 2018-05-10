# Release Notes

## Unreleased

### Added

### Changed
- 连接配置，回调函数的参数可选

### Removed

### Fixed

## [v1.0.3] - 2018-03-29

### Changed
- `join()`方法表名参数，抽象表名改为真实表名，表别名参数由可传入空字符串改为必须传入表别名且不能为空

### Fixed
- 当条件值为不支持的类型时，抛出异常

## [v1.0.2] - 2018-03-23

### Added
- 引入PDO工厂模块
- `ConnectionManager`类增加`getLogger()`方法
- 连接配置支持使用回调函数

### Changed
- 当客户端未传递日志组件时，连接管理器会创建一个`NullLogger`实例来代替，避免在使用日志组件时使用if判断
- `ConnectionInterface`接口变更

## [v1.0.1] - 2017-12-19

### Fixed
- 修复调用`AbstractConnection::getLastSql`方法会产生*Warning*消息的BUG