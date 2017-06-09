
-- 测试用的数据库
-- 数据库名称：bbsdb
-- 数据库用户名：bbs
-- 数据库密码：bbs
-- 表前缀：bbs_

-- 创建数据库
CREATE DATABASE IF NOT EXISTS bbsdb DEFAULT CHARSET utf8 COLLATE utf8_general_ci;

-- 创建用户及授权
CREATE USER 'bbs'@'%' IDENTIFIED BY  'bbs';
GRANT USAGE ON * . * TO  'bbs'@'%' IDENTIFIED BY  'bbs' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;
GRANT ALL PRIVILEGES ON  `bbsdb` . * TO  'bbs'@'%';


-- 表结构

-- 用户表
CREATE TABLE `bbs_user` (
 `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `username` varchar(15) NOT NULL COMMENT '用户名，3到15位。全系统唯一',
 `password` char(32) NOT NULL COMMENT 'md5 hash过的用户密码',
 `email` varchar(50) NOT NULL COMMENT '注册邮箱，一个邮箱只能注册一次',
 `nickname` varchar(50) NOT NULL COMMENT '用户昵称',
 `add_time` int(10) unsigned NOT NULL COMMENT '注册时间',
 PRIMARY KEY (`uid`),
 UNIQUE KEY `username` (`username`),
 UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户表';

-- (用户)发帖表
CREATE TABLE `bbs_post` (
 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `uid` int(10) unsigned NOT NULL COMMENT '所属用户id',
 `title` varchar(40) NOT NULL COMMENT '帖子标题',
 `content` text NOT NULL COMMENT '帖子内容',
 `add_time` int(10) unsigned NOT NULL COMMENT '帖子创建时间',
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='(用户)发帖表';