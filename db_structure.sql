
-- Дамп структуры для таблицы test-task.session_log
DROP TABLE IF EXISTS `session_log`;
CREATE TABLE IF NOT EXISTS `session_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `login_date` datetime DEFAULT NULL,
  `user_ip` varchar(20) DEFAULT NULL,
  `token` varchar(50) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- Дамп структуры для таблицы test-task.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(50) NOT NULL DEFAULT '',
  `password` varchar(50) NOT NULL DEFAULT '',
  `registered` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `users`(`login`,`password`) VALUES
('user', MD5('pass'));


-- Дамп структуры для таблицы test-task.comments
DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  `level` int(11) NOT NULL DEFAULT 0,
  `author_id` int(11) NOT NULL,
  `comment` varchar(4096) NOT NULL DEFAULT '',
  `created` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated` datetime DEFAULT 0 ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
  INDEX (`lft`,`rgt`)
  FOREIGN KEY (`author_id`) REFERENCES `users`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `comments`(`lft`,`rgt`,`level`,`comment`,`author_id`) VALUES
(1,2,0,'comment 1', 1),
(3,4,0,'comment 2', 1);