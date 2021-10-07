<?php

require_once(__DIR__ . '/../vendor/autoload.php');

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$dbh = new PDO("mysql:host=localhost:3306", 'root', '');

$dbh->exec("
DROP DATABASE IF EXISTS fruittest ;
CREATE DATABASE fruittest DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
use fruittest;
CREATE TABLE IF NOT EXISTS 
product (
    ID INT NOT NULL AUTO_INCREMENT, 
    name VARCHAR(100), 
    price DECIMAL(7,2), 
    total DECIMAL(7,2) NOT NULL DEFAULT 0.00, 
    PRIMARY KEY(id) )ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO product (name, price, total) VALUES  ('apple', 10.5, 25.2), ('raspberry',13, 0), ('strawberry', 7.8, 0);
");