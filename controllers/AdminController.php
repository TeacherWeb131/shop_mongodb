<?php


class AdminController extends Controller
{
    public function __construct()
    {
        $this->isAdminConnected();
    }

    // Route: /index.php/admin ou Route: /index.php/admin/index
    // dashboard pour l'administrateur
    // accessible en utilisant soit: monsite.fr/admin ou monsitefr/admin/index
    public function index()
    { 
        require "views/admin/index.php";
    }

    // Route: /index.php/admin/listproducts
    public function listproducts()
    {
        $products = Product::getAllProducts();

        require 'views/admin/list_product.php';
    }

    // Route: /index.php/admin/addproduct
    public function addproduct()
    {
        if(isset($_POST['submit']))
        {
            $product = new Product();
            $product->setName($_POST['name']);
            $product->setDescription($_POST['description']);
            $product->setPrice($_POST['price']);
            $product->setQuantity($_POST['quantity']);
            $product->setPicture_url($_POST['picture_url']);
            if($product->isValid())
            {
                $product->save();
                $this->redirectTo('/admin/listproducts');
            }
        }
        require "views/admin/add_product.php";
    }

    // Route: /index.php/admin/orders
    // afficher la liste des commandes
    public function orders()
    {
        $orders = Order::getAllOrders();
        
        require "views/admin/orders.php";
    }

    // détails d'une commande
    public function order($id)
    {
        $order = Order::getOrderById($id);
     
        require "views/admin/order_details.php";
    }


    // marquer la commande comme si livré
    public function shipped($id)
    {
        Order::editSubmittedAt($id);
        $this->redirectTo('/admin/orders');
    }
}
