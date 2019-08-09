<?php


class OrderDetails
{
    // proprietés
    private $id;
    private $quantity_ordered;
    private $price_each;
    private $total_price;
    private $order_id;
    private $product_id;

    

    // getter et setter


    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of quantity_ordered
     */ 
    public function getQuantity_ordered()
    {
        return $this->quantity_ordered;
    }

    /**
     * Set the value of quantity_ordered
     *
     * @return  self
     */ 
    public function setQuantity_ordered($quantity_ordered)
    {
        $this->quantity_ordered = $quantity_ordered;

        return $this;
    }

    /**
     * Get the value of price_each
     */ 
    public function getPrice_each()
    {
        return $this->price_each;
    }

    /**
     * Set the value of price_each
     *
     * @return  self
     */ 
    public function setPrice_each($price_each)
    {
        $this->price_each = $price_each;

        return $this;
    }

    /**
     * Get the value of total_price
     */ 
    public function getTotal_price()
    {
        return $this->total_price;
    }

    /**
     * Set the value of total_price
     *
     * @return  self
     */ 
    public function setTotal_price($total_price)
    {
        $this->total_price = $total_price;

        return $this;
    }

    /**
     * Get the value of order_id
     */ 
    public function getOrder_id()
    {
        return $this->order_id;
    }

    /**
     * Set the value of order_id
     *
     * @return  self
     */ 
    public function setOrder_id($order_id)
    {
        $this->order_id = $order_id;

        return $this;
    }

    /**
     * Get the value of product_id
     */ 
    public function getProduct_id()
    {
        return $this->product_id;
    }

    /**
     * Set the value of product_id
     *
     * @return  self
     */ 
    public function setProduct_id($product_id)
    {
        $this->product_id = $product_id;

        return $this;
    }




    // méthode d'insertion dans la base de données

    public function save()
    {
        $cnx = new Connexion();
        $cnx->querySQL(
            "INSERT INTO order_details (quantity_ordered, price_each, total_price, order_id, product_id) VALUES (?,?,?,?,?)",
            [
                $this->quantity_ordered,
                $this->price_each,
                $this->total_price,
                $this->order_id,
                $this->product_id
            ]
            );
    }

    public static function getOrderDetailsByOrder($id)
    {
        $cnx = new Connexion();
        $result = $cnx->getMany(
                    "SELECT * FROM order_details WHERE order_id = ?",
                    'OrderDetails',
                    [$id]
                ); 
        return $result;
    }
}