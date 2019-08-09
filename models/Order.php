<?php


class Order
{
    // proprietés
    private $id;
    private $created_at;
    private $submitted_at;
    private $total_ht;
    private $total_ttc;
    private $user_id;




    // getter et setter

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of created_at
     */
    public function getCreated_at()
    {
        return $this->created_at;
    }

    /**
     * Set the value of created_at
     *
     * @return  self
     */
    public function setCreated_at($created_at)
    {
        $this->created_at = $created_at;

        return $this;
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




    // méthode d'insertion dans la base de données
    public function save()
    {
        $cnx = new Connexion();
        $id =  $cnx->querySQL(
            "INSERT INTO `order` (created_at, total_ht, total_ttc, user_id) VALUES (?,?,?,?)",
            [
                date('Y-m-d H:i:s'),
                $this->total_ht,
                $this->total_ttc,
                $this->user_id
            ]
        );

        return $id;
    }


    public static function editSubmittedAt($id)
    {
        $cnx = new Connexion();
        $cnx->querySQL(
            "UPDATE `order` SET submitted_at = ? WHERE id = ?",
            [
                date("Y-m-d H:i:s"),
                $id
            ]
        );
    }

    // méthode de récupération d'une seule commande AVEC ses orderDetails 

    public static function getOrderById($id)
    {
        $cnx = new Connexion();
        $stmt = ($cnx->getPdo())->prepare("SELECT `order`.*, order_details.* FROM `order` JOIN order_details ON `order`.id = order_details.order_id WHERE `order`.id=?");
        $stmt->execute([$id]);
        $order = $stmt->fetchAll();
        return $order;
    }

    public static function getOrderByUserId($id)
    {
        $cnx = new Connexion();
        $orders = $cnx->getMany(
            "SELECT * FROM `order` WHERE user_id = ?",
            "Order",
            [$id]
        );

        return $orders;
    }

    public function getAllOrders()
    {
        // A changer et utilser la jointure pour récupérer les détails des commandes
        $cnx = new Connexion();
        return $cnx->getMany(
            "SELECT * FROM `order`",
            "Order"
        );
    }


   
}
