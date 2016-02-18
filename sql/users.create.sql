USE `api`;

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(24) NOT NULL,
  `approved` TINYINT UNSIGNED NOT NULL DEFAULT 1,
  `path` VARCHAR(96) NOT NULL,
  `posted` DATETIME NOT NULL,
  PRIMARY KEY (`id`)
);

INSERT INTO `users` (
  `id`,
  `name`,
  `approved`,
  `path`,
  `posted`
) VALUES
(1, 'ニックちゃん', 1, '20160215-012544.png', '2016-02-15 01:25:44'),
(2, 'うさ子', 0, '20160215-012544.png', '2016-02-15 01:25:44');


USE `api_test`;

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(24) NOT NULL,
  `approved` TINYINT UNSIGNED NOT NULL DEFAULT 1,
  `path` VARCHAR(96) NOT NULL,
  `posted` DATETIME NOT NULL,
  PRIMARY KEY (`id`)
);
