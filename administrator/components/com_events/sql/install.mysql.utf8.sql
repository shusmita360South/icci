CREATE TABLE IF NOT EXISTS `#__events_categories` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL DEFAULT '1',
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
`title` VARCHAR(255)  NOT NULL ,
`subtitle` VARCHAR(255)  NOT NULL ,
`color` VARCHAR(255)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__events_items` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
`catid` INT(11)  NOT NULL ,
`title` VARCHAR(255)  NOT NULL ,
`subtitle` VARCHAR(255)  NOT NULL ,
`intro` VARCHAR(255)  NOT NULL ,
`date` datetime NOT NULL,
`stime`  datetime NOT NULL,
`etime`  datetime NOT NULL,
`body` TEXT NOT NULL ,
`image` VARCHAR(255)  NOT NULL ,
`location` VARCHAR(255)  NOT NULL ,
`memberfee` VARCHAR(255)  NOT NULL ,
`nonmemberfee` VARCHAR(255)  NOT NULL ,
`link` VARCHAR(255)  NOT NULL ,
`host` VARCHAR(255)  NOT NULL ,
`partners` VARCHAR(255)  NOT NULL ,
`faq` VARCHAR(255)  NOT NULL ,
`relatedevents` VARCHAR(255)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;