<?php



class ProductController
{
    // sert à afficher la liste des produits
    // monsite.fr/product ou monsite.fr/product/index
    public function index()
    {
        $products = Product::getAllProducts();
        
        require 'views/product/list_product.php';
    }


    // monsite.fr/product/show/12
    public function show($id)
    {
        $product = Product::getProductById($id);
        
        require "views/product/show_product.php";
    }

    // Route: /index.php/...
    public function cart()
    {

        require "views/product/cart.php";
    }
}