CREATE TABLE IF NOT EXISTS `ipaddress_likes_map` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `tutorial_id` int(8) NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
)