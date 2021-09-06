
CREATE TABLE `wp_user_course` (
      `id` int(11) NOT NULL COMMENT 'ID',
      `user_id` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
      `source_user_id` int(11) UNSIGNED NOT NULL COMMENT '源用户ID',

      `signin_num` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '登陆次数',
      `signin_first` timestamp NULL DEFAULT NULL COMMENT '首次登录时间',
      `last_ip` varchar(15) NOT NULL DEFAULT '' COMMENT '上次登陆IP',
      `last_at` timestamp NULL DEFAULT NULL COMMENT '最后登录时间',
      `register_ip` varchar(15) NOT NULL DEFAULT '' COMMENT '注册IP',

      `created_at` timestamp NULL DEFAULT NULL COMMENT '注册时间',
      `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间'
  `status` varchar(20) NOT NULL DEFAULT '' COMMENT '用户状态'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `wp_user_course`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);
ALTER TABLE `wp_user_course`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID';


CREATE TABLE `el_user` (
      `uid` int(11) NOT NULL COMMENT '主键UID',
      `login` varchar(255) DEFAULT NULL COMMENT '登录emial',
      `password` varchar(255) DEFAULT NULL COMMENT '用户密码的md5摘要',
      `login_salt` char(5) DEFAULT NULL COMMENT '10000 到 99999之间的随机数，加密密码时使用',
      `uname` varchar(255) DEFAULT NULL COMMENT '用户名',
      `email` varchar(255) DEFAULT NULL COMMENT '用户email',
      `phone` varchar(16) DEFAULT NULL COMMENT '手机号',
      `sex` tinyint(1) NOT NULL DEFAULT '0' COMMENT '性别 1：男、2：女',
      `background_id` int(11) DEFAULT NULL COMMENT '用户个人主页背景',
      `location` varchar(255) DEFAULT NULL COMMENT '所在省市的字符串',
      `is_audit` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否通过审核：0-未通过，1-已通过',
      `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否已激活 1：激活、0：未激活',
      `is_init` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否初始化用户资料 1：初始化、0：未初始化',
      `ctime` int(11) DEFAULT NULL COMMENT '注册时间',
      `reg_ip` varchar(64) DEFAULT '127.0.0.1' COMMENT '注册注IP',
      `browser` varchar(50) DEFAULT NULL COMMENT '注册浏览器',
      `browser_ver` varchar(50) DEFAULT NULL COMMENT '注册浏览器版本号',
      `os` varchar(50) DEFAULT NULL COMMENT '注册操作系统',
      `place` varchar(50) DEFAULT NULL COMMENT '注册地区',
      `identity` tinyint(1) NOT NULL DEFAULT '1' COMMENT '身份标识（1：用户，2：组织）',
      `province` mediumint(6) DEFAULT '0' COMMENT '省ID、关联el_area表',
      `city` int(5) DEFAULT '0' COMMENT '城市ID，关联el_area表',
      `area` int(5) DEFAULT '0' COMMENT '地区ID，关联el_area表',
      `is_del` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否禁用，0不禁用，1：禁用',
      `first_letter` char(1) DEFAULT NULL COMMENT '用户名称的首字母',
      `intro` varchar(255) DEFAULT NULL COMMENT '户用简介',
      `profession` varchar(255) DEFAULT NULL COMMENT '用户职业',
      `last_login_time` int(11) DEFAULT '0' COMMENT '户用最后一次登录时间',
      `search_key` varchar(500) DEFAULT NULL COMMENT '搜索字段',
      `invite_code` varchar(120) DEFAULT NULL COMMENT '邀请注册码',
      `mail_activate` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '邮箱激活状态',
      `login_num` int(11) NOT NULL DEFAULT '0' COMMENT '登录次数',
      `phone_activate` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '手机激活状态',
      `mhm_id` int(11) NOT NULL DEFAULT '0' COMMENT '机构id',
      `binding_uid` int(11) NOT NULL DEFAULT '0' COMMENT '绑定上级用户id',
      `password2` varchar(255) DEFAULT NULL COMMENT '手机或邮箱用户的密码',
      `login_salt2` char(5) DEFAULT NULL COMMENT '手机邮箱用户安全码',
      `login2` varchar(255) DEFAULT NULL COMMENT '手机或邮箱用户名',
      `sourceuid` int(11) DEFAULT '0' COMMENT '班主任对应的中台backUserId',
      `credit` varchar(255) DEFAULT '0' COMMENT '打卡学分字段',
      `is_cleader` tinyint(5) DEFAULT '0' COMMENT '是否圈主，0表示否，1表示是',
      `is_helper` varchar(255) DEFAULT '0' COMMENT '是否助教点评，0：用户，1：是助教点评',
      `utype` tinyint(5) DEFAULT '0' COMMENT '0普通用户，1打卡小程序用户',
      `uedit` tinyint(2) DEFAULT '0' COMMENT '小程序是否拉取过详细信息，0为新用户，1为修改过的用户',
      `is_rubbing` tinyint(1) DEFAULT '0' COMMENT '是否碑帖小程序0:其他1:碑帖',
      `is_member` tinyint(1) DEFAULT '0' COMMENT '是否为班主任 0:不是 1:是',
      `member_time` int(11) DEFAULT '0' COMMENT '领取掌上碑帖会员时间',
      `introduction` varchar(255) DEFAULT NULL COMMENT '用户简介',
      `ios_user` varchar(255) DEFAULT NULL COMMENT 'ios审核的时候用户凭证',
      `is_copybook` tinyint(1) UNSIGNED DEFAULT '0' COMMENT '是否是电商字帖用户，0：否 、1：是'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
ALTER TABLE `el_user`
  ADD PRIMARY KEY (`uid`) USING BTREE,
  ADD KEY `login` (`login`) USING BTREE,
  ADD KEY `uname` (`uname`) USING BTREE,
  ADD KEY `phone` (`phone`) USING BTREE,
  ADD KEY `login2` (`login2`) USING BTREE,
  ADD KEY `index_0` (`mhm_id`),
  ADD KEY `index_1` (`is_cleader`,`uname`),
  ADD KEY `index_z` (`is_audit`,`is_active`,`is_init`,`uname`),
  ADD KEY `index_email` (`email`),
  ADD KEY `index_copybook` (`is_copybook`) USING BTREE,
  ADD KEY `sourceuid` (`sourceuid`);
ALTER TABLE `el_user`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键UID';

