#2010.12.21
ALTER TABLE `news`
	ADD COLUMN `events_id` MEDIUMINT(8) UNSIGNED NULL DEFAULT NULL AFTER `brands_id`,
	ADD INDEX `events_id` (`events_id`),
	ADD CONSTRAINT `news_ibfk_8` FOREIGN KEY (`events_id`) REFERENCES `events` (`id`) ON UPDATE CASCADE ON DELETE SET NULL;
