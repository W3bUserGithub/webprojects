--
-- MySQL 5.5.5
-- Mon, 29 Jul 2019 15:43:51 +0000
--

CREATE TABLE `blocked` (
   `id` int(11) not null auto_increment,
   `ip` varchar(120) not null,
   `SessionID` text not null,
   `chat` int(11) not null,
   PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1;

-- [Table `blocked` is empty]

CREATE TABLE `chatrooms` (
   `id` int(11) not null auto_increment,
   `title` varchar(60) not null,
   `description` varchar(250) not null,
   `url` varchar(250) not null,
   `image` varchar(250) not null,
   `lastupdate` varchar(120) not null,
   `LastActivityView` varchar(120) not null,
   `ChatStatus` int(2) not null,
   `owner` varchar(20) not null,
   `timestamp` timestamp not null default CURRENT_TIMESTAMP,
   PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1;

-- [Table `chatrooms` is empty]

CREATE TABLE `messages` (
   `id` int(11) not null auto_increment,
   `nickname` varchar(20) not null,
   `message` varchar(255) not null,
   `type` int(1) not null,
   `time` varchar(120) not null,
   `chat` int(11) not null,
   `timestamp` timestamp not null default CURRENT_TIMESTAMP,
   PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1;

-- [Table `messages` is empty]

CREATE TABLE `online` (
   `id` int(11) not null auto_increment,
   `username` varchar(20) not null,
   `LoginType` int(1) not null,
   `ip` varchar(120) not null,
   `SessionID` text not null,
   `chat` int(11) not null,
   `timestamp` timestamp not null default CURRENT_TIMESTAMP,
   PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1;

-- [Table `online` is empty]

CREATE TABLE `reports` (
   `id` int(11) not null auto_increment,
   `nickname` varchar(20) not null,
   `text` varchar(255) not null,
   `email` varchar(120) not null,
   `time` varchar(120) not null,
   `timestamp` timestamp not null default CURRENT_TIMESTAMP,
   PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1;

-- [Table `reports` is empty]

CREATE TABLE `users` (
   `userId` int(11) not null auto_increment,
   `userName` varchar(20) not null,
   `userEmail` varchar(120) not null,
   `userPass` varchar(20) not null,
   `userLevel` varchar(2) not null default '0',
   `userIp` varchar(120) not null,
   `userTimestamp` timestamp not null default CURRENT_TIMESTAMP,
   PRIMARY KEY (`userId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1;

-- [Table `users` is empty]