<?php

require 'vendor/autoload.php';
require 'utilities/config.php';
require 'utilities/Connexion.php';
require 'models/User.php';
require 'models/Order.php';
require 'models/OrderDetails.php';
require 'models/Product.php';

$faker = Faker\Factory::create('fr_FR');


// ajouter 5 utilisateurs

for($i =0 ; $i<5 ; $i++)
{
    $user = new User();
    $user->setFirst_name($faker->firstNameMale);
    $user->setLast_name($faker->lastName);
    $user->setEmail($faker->freeEmail);
    $user->setPassword("0000");
    $user->setAdmin(0);
    $user->save();
}

// ajouter 20 produits

for($i =0 ; $i<20 ; $i++)
{
    $product = new Product();
    $product->setName($faker->sentence(4));
    $product->setDescription($faker->text);
    $product->setPrice($faker->randomFloat(2, 10, 100));
    $product->setQuantity(rand(10,20));
    $product->setPicture($faker->imageUrl(250,250));
    //dump($product);
    $product->save();
}

// ajouter 30 commandes

for($i=0 ; $i<30; $i++)
{
    $order = new Order();
    $amount = $faker->randomFloat(2, 10, 100);
    $order->setTotal_ht($amount);
    $order->setTotal_ttc($order->getTotal_ht() * 1.2);
    $order->setUser_id(rand(1,5));
    
    $order_id =$order->save();
    
    // // ajouter entre 1 et 5 produits pour chaque commande
    $random_product = rand(1, 5);
    for ($j=1; $j <=$random_product ; $j++) { 
        $order_details = new OrderDetails();
        $order_details->setQuantity_ordered(rand(1,4));
        $order_details->setPrice_each($faker->randomFloat(2, 10, 100));
        $order_details->setTotal_price($order_details->getPrice_each() * $order_details->getQuantity_ordered());
        $order_details->setOrder_id($order_id);
        $order_details->setProduct_id(rand(1,20));
        $order_details->save();
    }
}