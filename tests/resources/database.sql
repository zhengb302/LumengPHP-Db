
/*
 * 测试用的数据库
 */

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

CREATE TABLE `bbs_user_profile` (
 `uid` int(10) unsigned NOT NULL COMMENT '用户id，主键',
 `sex` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '性别：1,男；0,女。默认为1',
 `location` varchar(30) NOT NULL COMMENT '居住地',
 `business` varchar(30) NOT NULL COMMENT '所属行业',
 `employment` varchar(80) NOT NULL COMMENT '所属公司或组织名称',
 `position` varchar(40) NOT NULL COMMENT '职位',
 `education` varchar(70) NOT NULL COMMENT '（毕业）学校或教育机构',
 `speciality` varchar(35) NOT NULL COMMENT '专业方向，如“软件工程”',
 `description` varchar(600) NOT NULL COMMENT '个人简介',
 PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户资料表';