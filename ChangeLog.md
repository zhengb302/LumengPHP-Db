# Release Notes

## Unreleased

### Added

### Changed

- 当客户端未传递日志组件时，连接管理器会创建一个`NullLogger`实例来代替，避免在使用日志组件时使用if判断

### Removed

### Fixed

## [v1.0.1] - 2017-12-19

### Fixed
- 修复调用`AbstractConnection::getLastSql`方法会产生*Warning*消息的BUG