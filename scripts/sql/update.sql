#2010.12.21
ALTER TABLE `news`
	ADD COLUMN `events_id` MEDIUMINT(8) UNSIGNED NULL DEFAULT NULL AFTER `brands_id`,
	ADD INDEX `events_id` (`events_id`),
	ADD CONSTRAINT `news_ibfk_8` FOREIGN KEY (`events_id`) REFERENCES `events` (`id`) ON UPDATE CASCADE ON DELETE SET NULL;

#2010.12.27
CREATE TABLE `online_showrooms` (
	`id` MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(128) NOT NULL DEFAULT '',
	`width` INT(11) UNSIGNED NOT NULL,
	`height` INT(11) UNSIGNED NOT NULL,
	`order` SMALLINT(5) UNSIGNED NULL DEFAULT NULL,
	PRIMARY KEY (`id`),
	INDEX `order` (`order`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;

CREATE TABLE `online_types` (
	`id` MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(128) NOT NULL DEFAULT '',
	`width` INT(11) UNSIGNED NOT NULL,
	`height` INT(11) UNSIGNED NOT NULL,
	`banner` TINYINT(1) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;

CREATE TABLE `online_places` (
	`id` MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
	`showrooms_id` MEDIUMINT(8) UNSIGNED NULL,
	`types_id` MEDIUMINT(8) UNSIGNED NULL,
	`companies_id` MEDIUMINT(8) UNSIGNED NULL,
	`top` INT(11) UNSIGNED NOT NULL,
	`left` INT(11) UNSIGNED NOT NULL,
	`showrooms_order` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '1',
	PRIMARY KEY (`id`),
	INDEX `showrooms_id` (`showrooms_id`),
	INDEX `types_id` (`types_id`),
	INDEX `companies_id` (`companies_id`),
	CONSTRAINT `online_places_ibfk_1` FOREIGN KEY (`showrooms_id`) REFERENCES `online_showrooms` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT `online_places_ibfk_2` FOREIGN KEY (`types_id`) REFERENCES `online_types` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT `online_places_ibfk_3` FOREIGN KEY (`companies_id`) REFERENCES `companies` (`id`) ON UPDATE CASCADE ON DELETE SET NULL
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;

INSERT INTO `online_showrooms` (`id`, `name`, `width`, `height`, `order`) VALUES
	(1, 'ExpoHall1', 968, 1340, 1);
INSERT INTO `online_types` (`id`, `name`, `width`, `height`, `banner`) VALUES
	(1, 'Stand 53x53', 53, 53, 0),
	(2, 'Stand 123x123', 123, 123, 0),
	(3, 'Stand 248x248', 248, 248, 0);
INSERT INTO `online_places` (`id`, `showrooms_id`, `types_id`, `companies_id`, `top`, `left`, `showrooms_order`) VALUES
	(1, 1, 1, NULL, 10, 10, 1),
	(2, 1, 2, 64156, 100, 20, 2),
	(3, 1, 3, NULL, 300, 100, 3);

#2010.12.30
ALTER TABLE `online_types`  CHANGE COLUMN `banner` `banner` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' AFTER `height`,  ADD COLUMN `size` SMALLINT(5) UNSIGNED NOT NULL DEFAULT '1' AFTER `banner`;
ALTER TABLE `online_places`  ADD COLUMN `logo` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' AFTER `id`;

#2011.01.10
ALTER TABLE `online_showrooms`  ADD COLUMN `brands_categories_id` SMALLINT(5) UNSIGNED NULL DEFAULT NULL AFTER `id`,  ADD INDEX `brands_categories_id` (`brands_categories_id`),  ADD CONSTRAINT `online_showrooms_ibfk_1` FOREIGN KEY (`brands_categories_id`) REFERENCES `brands_categories` (`id`) ON UPDATE CASCADE ON DELETE SET NULL;

UPDATE `online_showrooms` SET `brands_categories_id`=24;

#2011.02.01
UPDATE `types` SET `name`='Изображение 250x120', `height`=120, `width`=250 WHERE `id`=5 LIMIT 1;
UPDATE `types` SET `name`='Flash 250x120', `height`=120, `width`=250 WHERE `id`=6 LIMIT 1;
UPDATE `places` SET `name`='Центральный левый 250x120' WHERE `id`=3 LIMIT 1;
UPDATE `places` SET `name`='Центральный правый 250x120' WHERE `id`=4 LIMIT 1;
UPDATE `places` SET `name`='Ниже по списку левый 250x120' WHERE `id`=23 LIMIT 1;
UPDATE `places` SET `name`='Ниже по списку правый 250x120' WHERE `id`=24 LIMIT 1;