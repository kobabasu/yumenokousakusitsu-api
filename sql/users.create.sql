USE `api`;

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(70) NOT NULL,
  `email` VARCHAR(70) NOT NULL,
  PRIMARY KEY (`id`)
);

INSERT INTO `users` (
  `id`,
  `name`,
  `email`
) VALUES
(1, 'taro', 'taro@example.com'),
(2, 'hanako', 'hanako@example.com');


USE `api_test`;

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(70) NOT NULL,
  `email` VARCHAR(70) NOT NULL,
  PRIMARY KEY (`id`)
);
