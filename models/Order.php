<?php


class Order implements MongoDB\BSON\Serializable, MongoDB\BSON\Unserializable
{
    // proprietés
    private $_id;
    // On le supprime la propriété  '$created_at'
    // car l'objet $_id contient une date.
    // Donc quand on crée un objet Order une date est créée dans l'objet $_id,
    // Il existe des methodes pour récupérer cette date, c'est dans la méthode 'getCreated-at'
    // que l'on récupérer cette date au format timestamp et la transfomer en un format 'd-m-Y H:i:s
    // (De plus, le setter de created_at n'est pas utile (supprimé) car on n'a pas besoin de modifier cette date.)
    // (De plus, plus de created-at aussi dans le serialize)
    // private $created_at;
    private $submitted_at;
    private $total_ht;
    private $total_ttc;
    private $user_id;
    // LA PROPRIÉTÉ CI-DESSOUS CORRESPOND À L'OBJET 'orderDetails'
    // DE LA CLASSE 'orderDetails'. CETTE PROPRIÉTÉ REPRÉSENTE
    // LE LIEN ENTRE 'order' ET 'orderDetails'.
    // UN 'order' CONTIENT DES 'orderDetails'
    // DONC LA CLASSE 'order' CONTIENT UNE PROPRIÉTÉ ''orderDetails'
    private $orderDetails = [];

    // ON SERIALISE LES PROPRIÉTÉS (MONGODB)
    // CETTE METHODE EST À IMPLÉMENTER DE MANIÈRE OBLIGATOIRE
    // CAR ON A IMPLÉMENTÉ L'INTERFACE 'MongoDB\BSON\Serializable'
    // (CETTE METHODE EST UTILISÉ, DE MANIÈRE TRANSPARENTE POUR NOUS,
    // PAR LA METHODE insertOne() (METHODE MONGO) UTILISÉE PAR 'addToDb()' PAR EXEMPLE DANS LA FONCTION save()
    // POUR DESERIALISER L'OBJET PRODUCT À INSÉRER)
    // RAPPEL : SERIALISER C'EST TRANSFORMER UN OBJET EN JSON
    public function bsonSerialize()
    {
        return [
            // 'created_at' => new \MongoDB\BSON\UTCDateTime(new DateTime()),
            'submitted_at' => $this->submitted_at,
            'total_ht' => (float) $this->total_ht,
            'total_ttc' => (float) $this->total_ttc,
            'user_id' => $this->user_id,
            'orderDetails' => $this->orderDetails
        ];
    }

    // (MONGODB)
    // RAPPEL : DÉSERIALISER C'EST PASSER DE JSON EN OBJET
    public function bsonUnserialize(array $map)
    {
        foreach ($map as $key => $value) {
            if ($key == "orderDetails") {
                foreach ($map["orderDetails"] as $orderDetail) {
                    $od = new OrderDetails();
                    $od->set_id($orderDetail->_id);
                    $od->setQuantity_ordered($orderDetail->quantity_ordered);
                    $od->setPrice_each($orderDetail->price_each);
                    $od->setTotal_price($orderDetail->total_price);
                    $od->setProduct_id($orderDetail->product_id);
                    $this->orderDetail[] = $od;
                }
            } else {
                $this->$key = $value;
            }
        }
    }

    // getter et setter

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Get the value of created_at
     */
    public function getCreated_at()
    {
        $id_object = $this->_id;
        $timestamp = $id_object->getTimestamp();
        $date = new DateTime();
        $date->setTimestamp($timestamp);
        return $date->format('d/m/Y H:i:s');
    }


    /**
     * Get the value of submitted_at
     */
    public function getSubmitted_at()
    {
        return $this->submitted_at;
    }

    /**
     * Set the value of submitted_at
     *
     * @return  self
     */
    public function setSubmitted_at($submitted_at)
    {
        $this->submitted_at = $submitted_at;

        return $this;
    }



    /**
     * Get the value of user_id
     */
    public function getUser_id()
    {
        return $this->user_id;
    }

    /**
     * Set the value of user_id
     *
     * @return  self
     */
    public function setUser_id($user_id)
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * Get the value of total_ht
     */
    public function getTotal_ht()
    {
        return $this->total_ht;
    }

    /**
     * Set the value of total_ht
     *
     * @return  self
     */
    public function setTotal_ht($total_ht)
    {
        $this->total_ht = $total_ht;

        return $this;
    }

    /**
     * Get the value of total_ttc
     */
    public function getTotal_ttc()
    {
        return $this->total_ttc;
    }

    /**
     * Set the value of total_ttc
     *
     * @return  self
     */
    public function setTotal_ttc($total_ttc)
    {
        $this->total_ttc = $total_ttc;

        return $this;
    }


    // méthode d'insertion dans la base de données (MONGODB) (OK)
    public function save()
    {
        $cnx = new Connexion();
        $cnx->addToDb("order", $this);
    }

    // MONGODB
    public function getAllOrders()
    {
        $cnx = new Connexion();

        // RÉCUPÉRATION APRÈS LA REQUETE A LA BDD
        // LA COLLECTION $collection (TABLE EN SQL) 
        $arrayOrdersBsonDocumentObject = $cnx->findAll('order');
        // CONVERTIR LE RESULTAT RÉCUPÉRÉ (QUI EST UN OBJET BSON) EN FORMAT OBJET AVEC LA STRUCTURE PRODUCT
        // DONC JE DOIS DÉSERIALISER LE RESULTAT AVEC UNE METHODE PREDEFINI DANS L'API MONGO
        dd($arrayOrdersBsonDocumentObject);

        // JE PREPARE UN TABLEAU VIDE QUI VA CONTENIR MES OBJETS PHP
        // (CONVERTI EN STRING ET CONVERTI EN PHP DANS LA BOUCLE)
        $productsObjects = [];

        // JE DOIS BOUCLER SUR LE RESULTAT QUI EST UN TABLEAU D'OBJETS BSON
        // POUR CHAQUE $ProductsBsonDocumentObject  DANS LE TABLEAU $arrayProductsBsonDocumentObject
        foreach ($arrayProductsBsonDocumentObject as $oneProductsBsonDocumentObject) {
            // CONVERTIR LE BSON EN STRING
            $productString = MongoDB\BSON\fromPHP($oneProductsBsonDocumentObject);
            // dd($productString);

            // ON COMMUNIQUE $productString A LA FONCTION toPHP() QUI PREND 2 PARAMÈTRES
            // LE PREMIER : L'OBJET BSON TRANSFORMÉ EN STRING
            // LE DEUXIÈME : LA CLASSE CORRESPONDANT À L'OBJET COURANT (ICI 'Product')
            // ET CA NOUS RETOURNE UN OBJET PHP
            $productsObjects[] = MongoDB\BSON\toPHP($productString, ['root' => 'Product']);
        }
        return $productsObjects;
    }

    // // MYSQL - méthode d'insertion dans la base de données
    // public function save()
    // {
    //     $cnx = new Connexion();
    //     $id =  $cnx->querySQL(
    //         "INSERT INTO `order` (created_at, total_ht, total_ttc, user_id) VALUES (?,?,?,?)",
    //         [
    //             date('Y-m-d H:i:s'),
    //             $this->total_ht,
    //             $this->total_ttc,
    //             $this->user_id
    //         ]
    //     );

    //     return $id;
    // }


    // // MYSQL
    // public static function editSubmittedAt($id)
    // {
    //     $cnx = new Connexion();
    //     $cnx->querySQL(
    //         "UPDATE `order` SET submitted_at = ? WHERE id = ?",
    //         [
    //             date("Y-m-d H:i:s"),
    //             $id
    //         ]
    //     );
    // }

    // // méthode de récupération d'une seule commande AVEC ses orderDetails (MYSQL)
    // public static function getOrderById($id)
    // {
    //     $cnx = new Connexion();
    //     $stmt = ($cnx->getPdo())->prepare("SELECT `order`.*, order_details.* FROM `order` JOIN order_details ON `order`.id = order_details.order_id WHERE `order`.id=?");
    //     $stmt->execute([$id]);
    //     $order = $stmt->fetchAll();
    //     return $order;
    // }

    // // MYSQL
    // public static function getOrderByUserId($id)
    // {
    //     $cnx = new Connexion();
    //     $orders = $cnx->getMany(
    //         "SELECT * FROM `order` WHERE user_id = ?",
    //         "Order",
    //         [$id]
    //     );

    //     return $orders;
    // }

    // // MYSQL
    // public function getAllOrders()
    // {
    //     // A changer et utilser la jointure pour récupérer les détails des commandes
    //     $cnx = new Connexion();
    //     return $cnx->getMany(
    //         "SELECT * FROM `order`",
    //         "Order"
    //     );
    // }


   
}
