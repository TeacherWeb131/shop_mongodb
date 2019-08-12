<?php


class UserController extends Controller
{
    // dashboard pour le client
    // accessible en utilisant soit: monsite.fr/user ou monsitefr/user/index
    public function index()
    {
        $this->isConnected();
        require "views/user/index.php";
    }

    // Route: /index.php/login
    public function login()
    {
        
        $error = null;
        if (isset($_POST['cnx'])) {
            $user = User::getUserByEmail($_POST['email']);
            if ($user == false) {
                $error = "L'adresse mail n'existe pas";
            } else {
                if (password_verify($_POST['password'], $user->getPassword())) {
                    // La partie de la session // Création d'une classe  qui permet de gérer la session
                    $session = new Session();
                    $session->onUserLogin($user);
                    if($session->isAdmin())
                    {
                        $this->redirectTo("/admin");

                    }else{

                        $this->redirectTo("/user");
                    }
                } else {
                    $error = "Le mot de passe saisie est faut";
                }
            }
        }
        require 'views/user/login.php';
    }

    public function register()
    {
        
        $error = null;
        if (isset($_POST["valider"])) {
            $user = new User();
            $user->setFirst_name($_POST["first_name"]);
            $user->setLast_name($_POST["last_name"]);
            $user->setEmail($_POST["email"]);
            $user->setPassword($_POST['password']);
            // POUR MONGODB 'FALSE' AU LIEU DE 0, POUR QUE MONGODB INSERE CETTE DONNÉE EN BOOLEEN
            $user->setAdmin(FALSE);
            if ($user->isValid()) {
                $user->save();
                $this->redirectTo("/user");
            } else {
                $error = "Les données saisies sont invalides";
            }
        }

        require "views/user/register.php";
    }


    // Déconnection 
    public function logout()
    {
        $session = new Session();
        $session->destroy();
        $this->redirectTo("/user/login");
    }

    // affichage de la page du profile
    public function profile()
    {
        $this->isConnected();
        $session = new Session();
        // dd($_SESSION);
        $user = User::getUserById($session->getLoggedUserId());
        require "views/user/profile.php";
    }

    // liste des commandes pour un utilisateur connecté
    public function orders()
    {
        $this->isConnected();
        $session = new Session();
        $orders = Order::getOrderByUserId($session->getLoggedUserId());
        
        require "views/user/orders.php";
    }

    // détails d'une commande
    public function order($id)
    {
        $this->isConnected();
        $order = Order::getOrderById($id);
     
        require "views/user/order_details.php";
    }

    // validation d'une commande
    public function validateorder()
    {
        $this->isConnected();
        // je decompose la commande stocké dans le panier donc dans le cookie
        // et je décompose l'ensemble des produits qui sont séparés par un pipe |
        // en un seul produit
        $orders_in_cookie = explode("|", $_COOKIE['cart']);
        $total=0;
        foreach($orders_in_cookie as $o)
        {
            $details = explode(",", $o);
            $total += $details[2]*$details[3];
        }
        $order = new Order();
        $order->setTotal_ht($total);
        $order->setTotal_ttc($total * 1.2);
        $session = new Session();
        $order->setUser_id($session->getLoggedUserId());
        // on insere la commande et on recupere l'id de la commande
        $order_id = $order->save();
        // on crée les orderDetails
        foreach($orders_in_cookie as $o)
        {
            // Je décompose chaque produits qui sont séparés par une virgule
            $details = explode(",", $o);
            $order_details = new OrderDetails();
            $order_details->setQuantity_ordered($details[3]);
            $product = Product::getProductById($details[0]);
            $order_details->setPrice_each($product->getPrice());
            $order_details->setTotal_price($product->getPrice()*$details[3]);
            $order_details->setOrder_id($order_id);
            $order_details->setProduct_id($product->getId());
            $order_details->save();

            $product->setQuantity($product->getQuantity() - $details[3]);
            $product->update();

        }

        unset($_COOKIE['cart']);
        setcookie('cart', null, -1, '/'); 

        $this->redirectTo("/user/order/$order_id");

    }
}
