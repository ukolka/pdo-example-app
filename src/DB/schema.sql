DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int UNSIGNED AUTO_INCREMENT NOT NULL,
  `username` varchar(50) NOT NULL UNIQUE,
  `password` varchar(32) NOT NULL,
  `description` varchar(500) NOT NULL,
  `level` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`)
);

