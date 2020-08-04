CREATE TABLE IF NOT EXISTS `#__generalpages_categories` (
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

CREATE TABLE IF NOT EXISTS `#__generalpages_items` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
`catid` INT(11)  NOT NULL ,
`title` VARCHAR(255)  NOT NULL ,
`intro` VARCHAR(255)  NOT NULL ,
`image1` VARCHAR(255)  NOT NULL ,
`subtitle1` VARCHAR(255)  NOT NULL ,
`body1` TEXT NOT NULL ,
`subtitle2` VARCHAR(255)  NOT NULL ,
`body2` TEXT NOT NULL ,
`image2` VARCHAR(255)  NOT NULL ,
`twocolsbody` TEXT NOT NULL ,
`cardstitle` VARCHAR(255)  NOT NULL ,
`cardsintro` VARCHAR(255)  NOT NULL ,
`cards` TEXT NOT NULL ,
`cardstitle2` VARCHAR(255)  NOT NULL ,
`cardsintro2` VARCHAR(255)  NOT NULL ,
`cards2` TEXT NOT NULL ,
`videolink` VARCHAR(255)  NOT NULL ,
`relatedevents` VARCHAR(255)  NOT NULL ,
`template` INT(11)  NOT NULL ,

PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;