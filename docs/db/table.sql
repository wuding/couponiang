-- Adminer 4.6.2 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `alimama_choice_excel`;
CREATE TABLE `alimama_choice_excel` (
  `excel_id` int(11) NOT NULL AUTO_INCREMENT,
  `choice_id` int(3) DEFAULT '0' COMMENT '清单编号',
  `group` int(10) DEFAULT '0',
  `class` text COLLATE utf8mb4_unicode_ci,
  `shop` text COLLATE utf8mb4_unicode_ci,
  `platform` text COLLATE utf8mb4_unicode_ci,
  `item` text COLLATE utf8mb4_unicode_ci,
  `coupon` text COLLATE utf8mb4_unicode_ci,
  `name` text COLLATE utf8mb4_unicode_ci,
  `url` text COLLATE utf8mb4_unicode_ci,
  `taobaoke` text COLLATE utf8mb4_unicode_ci,
  `pic` text COLLATE utf8mb4_unicode_ci,
  `price` double DEFAULT '0',
  `ratio` text COLLATE utf8mb4_unicode_ci,
  `commission` text COLLATE utf8mb4_unicode_ci,
  `begin` text COLLATE utf8mb4_unicode_ci,
  `denomination` text COLLATE utf8mb4_unicode_ci,
  `cost` double DEFAULT '0',
  `total` text COLLATE utf8mb4_unicode_ci,
  `remain` text COLLATE utf8mb4_unicode_ci,
  `start` datetime DEFAULT NULL,
  `end` datetime DEFAULT NULL,
  `promotion` text COLLATE utf8mb4_unicode_ci,
  `link` text COLLATE utf8mb4_unicode_ci,
  `wangwang` text COLLATE utf8mb4_unicode_ci,
  `seller` text COLLATE utf8mb4_unicode_ci,
  `note` text COLLATE utf8mb4_unicode_ci,
  `full` text COLLATE utf8mb4_unicode_ci,
  `discount` double DEFAULT '0',
  `sale` int(10) DEFAULT '0',
  `status` tinyint(1) DEFAULT '0' COMMENT '-1已删除 1未入库 2已入库 3未更新 4已更新',
  `created` int(10) DEFAULT '0' COMMENT '首次创建时间',
  `updated` int(10) DEFAULT '0' COMMENT '仅更新时间',
  `modified` int(10) DEFAULT '0' COMMENT '数据修改时间',
  `csv` int(3) DEFAULT '0' COMMENT '历史版本数量',
  PRIMARY KEY (`excel_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='精选清单原始';


-- 2018-08-30 09:31:46

DROP TABLE IF EXISTS `alimama_choice_csv`;
CREATE TABLE `alimama_choice_csv` (
  `csv_id` int(11) NOT NULL AUTO_INCREMENT,
  `excel_id` int(10) DEFAULT '0',
  `choice_id` int(3) DEFAULT '0' COMMENT '清单编号',
  `group` int(10) DEFAULT '0',
  `class` text COLLATE utf8mb4_unicode_ci,
  `shop` text COLLATE utf8mb4_unicode_ci,
  `platform` text COLLATE utf8mb4_unicode_ci,
  `item` text COLLATE utf8mb4_unicode_ci,
  `coupon` text COLLATE utf8mb4_unicode_ci,
  `name` text COLLATE utf8mb4_unicode_ci,
  `url` text COLLATE utf8mb4_unicode_ci,
  `taobaoke` text COLLATE utf8mb4_unicode_ci,
  `pic` text COLLATE utf8mb4_unicode_ci,
  `price` text COLLATE utf8mb4_unicode_ci,
  `ratio` text COLLATE utf8mb4_unicode_ci,
  `commission` text COLLATE utf8mb4_unicode_ci,
  `begin` text COLLATE utf8mb4_unicode_ci,
  `denomination` text COLLATE utf8mb4_unicode_ci,
  `cost` text COLLATE utf8mb4_unicode_ci,
  `total` text COLLATE utf8mb4_unicode_ci,
  `remain` text COLLATE utf8mb4_unicode_ci,
  `start` text COLLATE utf8mb4_unicode_ci,
  `end` text COLLATE utf8mb4_unicode_ci,
  `promotion` text COLLATE utf8mb4_unicode_ci,
  `link` text COLLATE utf8mb4_unicode_ci,
  `wangwang` text COLLATE utf8mb4_unicode_ci,
  `seller` text COLLATE utf8mb4_unicode_ci,
  `note` text COLLATE utf8mb4_unicode_ci,
  `full` text COLLATE utf8mb4_unicode_ci,
  `discount` text COLLATE utf8mb4_unicode_ci,
  `sale` text COLLATE utf8mb4_unicode_ci,
  `difference` text COLLATE utf8mb4_unicode_ci COMMENT '与后一个不同的列名',
  `modified` int(10) DEFAULT '0' COMMENT '原修改时间',
  `compared` int(10) DEFAULT '0' COMMENT '比较时间',
  `seq` int(3) DEFAULT '-1' COMMENT '顺序',
  PRIMARY KEY (`csv_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='精选清单变化';


DROP TABLE IF EXISTS `alimama_choice_list`;
CREATE TABLE `alimama_choice_list` (
  `list_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '列表ID',
  `excel_id` int(11) DEFAULT '0' COMMENT '表格ID',
  `category_id` int(11) DEFAULT '0' COMMENT '分类ID',
  `item_id` bigint(20) DEFAULT '0' COMMENT '商品ID',
  `site` tinyint(1) DEFAULT '0' COMMENT '站点ID',
  `title` text COLLATE utf8mb4_unicode_ci COMMENT '商品标题',
  `pic` text COLLATE utf8mb4_unicode_ci COMMENT '图片地址',
  `price` double DEFAULT '-1' COMMENT '现价',
  `save` double DEFAULT '-1' COMMENT '节省',
  `cost` double DEFAULT '-1' COMMENT '原价',
  `sold` int(11) DEFAULT '-1' COMMENT '月销量',
  `start` datetime DEFAULT NULL COMMENT '开始时间',
  `end` datetime DEFAULT NULL COMMENT '结束时间',
  `status` tinyint(1) DEFAULT '0' COMMENT '状态',
  `updated` int(10) DEFAULT '0' COMMENT '更新时间戳',
  `created` int(10) DEFAULT '0' COMMENT '创建时间戳',
  PRIMARY KEY (`list_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='精选清单精简';


CREATE TABLE IF NOT EXISTS `alimama_product_category` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '分类ID',
  `upper_id` int(10) DEFAULT '0' COMMENT '上级ID',
  `class_id` bigint(20) DEFAULT '-1' COMMENT '原分类ID',
  `title` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '分类标题',
  `total` int(10) DEFAULT '-1' COMMENT '商品数量',
  `updated` int(10) DEFAULT '0' COMMENT '更新时间戳',
  `created` int(10) DEFAULT '0' COMMENT '创建时间戳',
  PRIMARY KEY (`category_id`),
  UNIQUE KEY `title` (`title`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='淘宝联盟商品分类' AUTO_INCREMENT=149 ;


-- 2018-08-30 09:35:12

-- Adminer 4.7.1 MySQL dump

DROP TABLE IF EXISTS `search_suggestions`;
CREATE TABLE `search_suggestions` (
  `suggestion_id` int(11) NOT NULL AUTO_INCREMENT,
  `term_id` int(10) DEFAULT '0',
  `complection` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seq` int(10) DEFAULT '0',
  `total` int(10) DEFAULT '-1',
  PRIMARY KEY (`suggestion_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='搜索建议';

INSERT INTO `search_suggestions` (`suggestion_id`, `term_id`, `complection`, `description`, `url`, `seq`, `total`) VALUES
(1, 4,  '双人床',  NULL, NULL, 0,  23),
(2, 4,  '床垫', NULL, NULL, 1,  3);

DROP TABLE IF EXISTS `search_terms`;
CREATE TABLE `search_terms` (
  `term_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '查询ID',
  `upper_id` int(10) DEFAULT '0' COMMENT '上级ID',
  `equal_id` int(10) DEFAULT '0' COMMENT '等同ID',
  `query` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '查询关键字',
  `results` int(10) DEFAULT '-1' COMMENT '结果数量',
  `suggestions` int(10) DEFAULT '-1' COMMENT '建议数量',
  `created` int(10) DEFAULT '0' COMMENT '创建时间戳',
  `modified` int(10) DEFAULT '0' COMMENT '建议修改时间戳',
  `updated` int(10) DEFAULT '0' COMMENT '更新时间戳',
  `updates` int(10) DEFAULT '0' COMMENT '更新次数',
  PRIMARY KEY (`term_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='搜索关键字';


-- 2019-02-19 15:32:55