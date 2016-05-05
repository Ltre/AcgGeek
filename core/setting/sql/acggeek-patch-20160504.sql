-- ----------------------------
-- 5sing单曲库
-- @since 2016-05-04
-- HP localhost 已同步
-- Office localhost 已同步
-- CONOHA 已同步
-- ----------------------------
CREATE TABLE `agk_mixed` (
  `mid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '代号,用于标识配置项',
  `content` longtext NOT NULL COMMENT '配置值(多个值存储时需序列化)',
  `note` varchar(64) NOT NULL DEFAULT '' COMMENT '注释',
  `create_ip` varchar(15) NOT NULL DEFAULT '',
  `create_time` int(11) NOT NULL,
  `update_ip` varchar(15) NOT NULL DEFAULT '',
  `update_time` int(11) NOT NULL,
  `create_user` bigint(20) NOT NULL COMMENT '创建人的user_id',
  `update_user` bigint(20) NOT NULL COMMENT '修改人的user_id',
  `valid` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否有效',
  PRIMARY KEY (`mid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;