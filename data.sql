CREATE TABLE `tbl_users` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `name` varchar(120) DEFAULT NULL,
 `email` varchar(120) DEFAULT NULL,
 `phone_no` varchar(30) DEFAULT NULL,
 `password` varchar(120) DEFAULT NULL,
 `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;