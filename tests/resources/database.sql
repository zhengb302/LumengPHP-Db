
-- 测试用的数据库
-- 数据库名称：bbsdb
-- 数据库用户名：bbs
-- 数据库密码：bbs
-- 表前缀：bbs_

CREATE TABLE `bbs_user` (
 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `username` varchar(15) NOT NULL COMMENT '用户名，3到15位。全系统唯一',
 `password` varchar(80) NOT NULL COMMENT '用户密码hash',
 `email` varchar(50) NOT NULL COMMENT '注册邮箱，一个邮箱只能注册一次',
 `nickname` varchar(50) NOT NULL COMMENT '用户昵称，全系统唯一',
 `avatar` varchar(30) NOT NULL COMMENT '头像名称，不包含扩展名和大小信息，如 d4a578fab，用户上传的头像都会被转化为jpg格式。',
 `register_time` int(10) unsigned NOT NULL COMMENT '注册时间',
 PRIMARY KEY (`id`),
 UNIQUE KEY `username` (`username`),
 UNIQUE KEY `nickname` (`nickname`),
 UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户表';

CREATE TABLE `bbs_post` (
 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `uid` int(10) unsigned NOT NULL COMMENT '所属用户id',
 `title` varchar(40) NOT NULL COMMENT '帖子标题',
 `content` text NOT NULL COMMENT '帖子内容',
 `add_time` int(10) unsigned NOT NULL COMMENT '帖子创建时间',
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='(用户)发帖表';