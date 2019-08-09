<?php

require "vendor/autoload.php";
require "utilities/config.php";
require "utilities/Connexion.php";
require "utilities/Session.php";

require "models/Order.php";
require "models/OrderDetails.php";
require "models/Product.php";
require "models/User.php";

require "utilities/Controller.php";
require "controllers/AdminController.php";
require "controllers/DefaultController.php";
require "controllers/ProductController.php";
require "controllers/UserController.php";
//monsite.fr/index.php/produit/afficher/45 $_SERVER['REQUEST_URI']

// définir l'url de base
define("REQUEST_URL", "http://".$_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME']);
define("ASSETS", "http://".$_SERVER['HTTP_HOST'].DIRECTORY_SEPARATOR."views".DIRECTORY_SEPARATOR."assets".DIRECTORY_SEPARATOR);
// récupérer  $_SERVER['REQUEST_URI']

$uri = $_SERVER['REQUEST_URI'];
//$uri = /index.php/produit/afficher/54
// supprimer de cette chaine de characteres /index.php/

$uri = str_replace("/index.php", "", $uri);
//$uri = produit/afficher/54



// convertir la partie restante (aaa/bb/vv) en tableau
$params = explode("/", $uri);
//var_dump($params);

//vérifier si l'URI ne contient rien
//=> on éxécute index du controlleur default

if (empty($params[1]) || !isset($params[1])) {
    // instencier le controlleur Default et executer sa méthode index
    $controlleur = new DefaultController();
    $controlleur->index();
} else {
    $controlleur = $params[1];
    $controlleur = ucfirst($controlleur) . "Controller";
    $instance = new $controlleur();
    if (empty($params[2]) || !isset($params[2])) {
        $instance->index();
    } else {
        $methode = $params[2];
        if (empty($params[3]) || !isset($params[3])) {
            $parameter = null;
        } else {
            $parameter = $params[3];
        }
        $instance->$methode($parameter);
    }
}




//instancier le controlleur (aaa)



// éxécuter la fonction (bb)



//récupérer les parametres (vv) si on aura besoin
