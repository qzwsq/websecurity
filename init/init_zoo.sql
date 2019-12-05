ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY '123456';
CREATE DATABASE `websecurity`;
USE `websecurity`;
CREATE TABLE `Person`
(
    PersonID int NOT NULL auto_increment,
    Username varchar(255) NOT NULL,
    Password varchar(255) NOT NULL,
    Salt varchar(255) default 0,
    Zoobars int default '1',
    Token varchar(255) default 'null',
    Profile TEXT,
    PRIMARY KEY (PersonId)
);

