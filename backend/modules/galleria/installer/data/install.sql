CREATE TABLE `galleria_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language` varchar(5) NOT NULL,
  `title` varchar(255) NOT NULL,
  `publish_on` datetime NOT NULL,
  `meta_id` int(11) NOT NULL,
  `sequence` int(11) NOT NULL,
  `hidden` enum('Y','N') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`id`)
) 
  ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `galleria_albums` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `meta_id` int(11) NOT NULL,
  `language` varchar(5) NOT NULL,
  `description` varchar(255),
  `publish_on` datetime NOT NULL,
  `hidden` enum('Y','N') NOT NULL DEFAULT 'N',
  `sequence` int(11) NOT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `galleria_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `album_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `language` varchar(5) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `description` varchar(255),
  `publish_on` datetime NOT NULL,
  `hidden` enum('Y','N') NOT NULL DEFAULT 'N',
  `sequence` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) 
  ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;