ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY '123456';
CREATE DATABASE `websecurity`;
USE `websecurity`;
CREATE TABLE `Person`
(
    PersonID int NOT NULL auto_increment,
    Username varchar(255) NOT NULL,
    Password varchar(255) NOT NULL,
    Salt int default 0,
    PRIMARY KEY (PersonId)
);
INSERT INTO `Person` values (1, 'admin', '8af8ba533d76052babaf51113d6c5449', 1000);
