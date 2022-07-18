CREATE DATABASE sistem_atm_sederhana;

USE sistem_atm_sederhana;

 CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `no_kartu` varchar(30) NOT NULL,
  `pin` varchar(5) NOT NULL,
  `saldo` int(11) NOT NULL DEFAULT 0,
  `creation_date` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB

INSERT INTO user (no_kartu, pin, saldo) VALUES ("12345", "12345", 2000000);

