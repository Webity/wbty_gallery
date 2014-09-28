
		
	CREATE TABLE IF NOT EXISTS `#__wbty_gallery_images` (
	`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`ordering` INT(11)  NOT NULL ,
	`state` TINYINT(1)  NOT NULL DEFAULT '1',
	`checked_out` INT(11)  NOT NULL ,
	`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`created_by` INT(11)  NOT NULL ,
	`created_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`modified_by` INT(11)  NOT NULL ,
	`modified_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`name` VARCHAR(255) NOT NULL,
	`image` VARCHAR(500) NOT NULL,
	`caption` LONGTEXT NOT NULL,
	`category` INT(11) NOT NULL,
	`menu_link` VARCHAR(255) NOT NULL,
	`for_sale` VARCHAR(100) NOT NULL,
	`pricing_set` INT(11) NOT NULL,
	`base_id` INT(11)  NOT NULL ,
	`custom_menu_link` VARCHAR(500) NOT NULL,
	`page_heading` VARCHAR(255) NOT NULL,
	`metadesc` TEXT NOT NULL,
	`metakey` TEXT NOT NULL,	
	PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT COLLATE=utf8_general_ci;
				