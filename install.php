<?php
// POUR UTILISER CE PROJET LA PREMIERE FOIS, IL FAUT :
    // 1. MODIFIER LE FICHIER config.php AVEC SES DONNÉES
    // 2. IL FAUT LANCER LE SERVEUR SUR LE PROJET 'shop'
    // 3. IL FAUT CRÉER LA BASE DE DONNÉES DANS PHPMYADMIN
    // 4. IL FAUT LANCER DANS LE NAVIGATEUR localhost/install.php QUI VA CRÉER TOUTES LES TALES UTILES AU PROJET 'shop'
    
require "utilities/config.php";
try {
    $cnx = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
} catch (PDOException $e) {
    echo $e->getMessage();
    die;
}



$sql_table1 = "CREATE TABLE user
    ( id INT NOT NULL AUTO_INCREMENT , 
    first_name VARCHAR(50) NOT NULL , 
    last_name VARCHAR(50) NOT NULL , 
    email VARCHAR(255) NOT NULL , 
    created_at DATETIME NOT NULL ,
    password varchar(255) NOT NULL,
    admin tinyint(1) NOT NULL, 
    PRIMARY KEY (id)) 
    ENGINE=InnoDB DEFAULT CHARSET=utf8";

$cnx->query($sql_table1);

$sql_table2 = "CREATE TABLE `order` (
    id int(11) NOT NULL AUTO_INCREMENT ,
    created_at datetime NOT NULL,
    submitted_at datetime DEFAULT NULL,
    total_ht float NOT NULL,
    total_ttc float NOT NULL,
    user_id int(11) NOT NULL,
    PRIMARY KEY (id)) 
    ENGINE=InnoDB DEFAULT CHARSET=utf8";

$cnx->query($sql_table2);

$sql_table3 = "CREATE TABLE `order_details` (
    id int(11) NOT NULL AUTO_INCREMENT ,
    quantity_ordered int(11) NOT NULL,
    price_each float NOT NULL,
    total_price float NOT NULL,
    order_id int(11) NOT NULL,
    product_id int(11) NOT NULL,
    PRIMARY KEY (id)) 
    ENGINE=InnoDB DEFAULT CHARSET=utf8";

$cnx->query($sql_table3);

$sql_table4 = "CREATE TABLE product (
    id int(11) NOT NULL AUTO_INCREMENT ,
    name varchar(255) NOT NULL,
    description text NOT NULL,
    price float NOT NULL,
    quantity int(11) NOT NULL,
    picture_url varchar(255) NOT NULL,
    PRIMARY KEY (id)) 
    ENGINE=InnoDB DEFAULT CHARSET=utf8";

$cnx->query($sql_table4);

$sql_juncture1 = "ALTER TABLE `order`
    ADD CONSTRAINT order_ibfk_1
    FOREIGN KEY (user_id) 
    REFERENCES user (id) 
    ON DELETE NO ACTION ON UPDATE NO ACTION";

$cnx->query($sql_juncture1);

$sql_juncture2_3 = "ALTER TABLE order_details
    ADD CONSTRAINT order_details_ibfk_1 
    FOREIGN KEY (product_id) 
    REFERENCES product (`id`) 
    ON DELETE NO ACTION ON UPDATE NO ACTION,
    ADD CONSTRAINT order_details_ibfk_2
    FOREIGN KEY (order_id) 
    REFERENCES `order` (id)";

$cnx->query($sql_juncture2_3);

header("Location:  http://" . $_SERVER['HTTP_HOST'] . "/install_fake_data.php");