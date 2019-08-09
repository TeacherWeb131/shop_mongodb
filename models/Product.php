<?php



class Product implements MongoDB\BSON\Serializable, MongoDB\BSON\Unserializable
{
    private $_id;
    private $name;
    private $description;
    private $price;
    private $quantity;
    private $picture_url;

    // ON SERIALISE LES PROPRIÉTÉS (MONGODB)
    // CETTE METHODE EST À IMPLÉMENTER DE MANIÈRE OBLIGATOIRE
    // CAR ON A IMPLÉMENTÉ L'INTERFACE 'MongoDB\BSON\Serializable'
    // (CETTE METHODE EST UTILISÉ, DE MANIÈRE TRANSPARENTE POUR NOUS,
    // PAR LA METHODE insertOne() (METHODE MONGO) UTILISÉE PAR 'addToDb()' PAR EXEMPLE DANS LA FONCTION save()
    // POUR DESERIALISER L'OBJET PRODUCT À INSÉRER)
    public function bsonSerialize()
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'price' => (float) $this->price,
            'quantity' => (int) $this->quantity,
            'picture_url' => $this->picture_url
        ];
    }

    // (MONGODB)
    // DANS LES METHODES DE RÉCUPÉRATION DE DONNÉES DE LA BDD, ON RÉCUPÈRE UN TABLEAU D'OBJETS BSON DONC
    // ON A BESOIN DE DESERIALISER LES OBJETS CONTENU DANS CE TABLEAU (ARRAY) 
    // CETTE METHODE EST À IMPLÉMENTER DE MANIÈRE OBLIGATOIRE
    // CAR ON A IMPLÉMENTÉ L'INTERFACE 'MongoDB\BSON\Unserializable'
    // (CETTE MÉTHODE EST UTILISÉ DE MANIÈRE TRANSPARENTE PAR MONGO 
    // AVEC LA METHODE '...' POUR DESERIALISER LE RETOUR DE NOTRE FUNCTION DE RECUPERATION
    // DE DONNÉE COMME getProductById($id) QUI NOUS RENVOIE UN OBJET BSON
    // AVEC A L'INTERIEUR UNE PROPRIÉTÉ 'STORAGE' QUI CONTIENT UN TABLEAU)
    public function bsonUnserialize(array $map)
    {
        foreach ($map as $key => $value) {
            $this->$key = $value;
        }
        // unserialized : PROPRIÉTÉ DEFINI DANS L'INTERFACE 'MongoDB\BSON\Unserializable'
        // CETTE PROPRIÉTÉ N'EST PAS OBLIGATOIRE DANS CETTE FONCTION,
        // MAIS ELLE PEUT ETRE UTILE EN MODE DEV POUR VERIFIER DANS UN DUMP
        // SI L'OBJET (ICI LE PRODUCT) A BIEN ÉTÉ DÉSÉRIALISÉ.
        // DANS LE DUMP, UNE PROPRIÉTÉ 'unserialized' EST RAJOUTÉ A L'OBJET
        // AVEC 'true' COMME VALEUR SI L'OBJET A BIEN SÉRIALISÉ ET 'false' SINON.
            // $this->unserialized = true;
    }
    
    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     * @return  self
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }
    /**
     * Get the value of picture_url
     */ 
    public function getPicture_url()
    {
        return $this->picture_url;
    }

    /**
     * Set the value of picture_url
     *
     * @return  self
     */ 
    public function setPicture_url($picture_url)
    {
        $this->picture_url = $picture_url;

        return $this;
    }
    /**
     * Get the value of price
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set the value of price
     *
     * @return  self
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get the value of quantity
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set the value of quantity
     *
     * @return  self
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

 

    public function isValid()
    {
        return true;
    }

    // FONCTION D'INSERTION D'UN PRODUIT (MONGO DB) (OK)
    public function save()
    {
        $cnx = new Connexion();
        // LA FONCTION addToDb() VA UTILISER, DE MANIÈRE TRANSPARENTE POUR NOUS,
        // LA FONCTION IMPLÉMENTÉ DANS CETTE CLASSE
        $cnx->addToDb("product", $this);
    }

    // // AJOUTER UN PRODUIT (BDD MYSQL)
    // public function save()
    // {
    //     $cnx = new Connexion();
    //     $cnx->querySQL(
    //         "INSERT INTO product (name, description, price, quantity, picture_url) VALUES (?,?,?,?,?)",
    //         [
    //             $this->name,
    //             $this->description,
    //             $this->price,
    //             $this->quantity,
    //             $this->picture_url
    //         ]
    //         );
    // }

    // RÉCUPÉRER UN SEUL PRODUIT (MONGO DB)
    public static function getProductById($id)
    {
        $cnx = new Connexion();

        // RÉCUPÉRATION APRÈS LA REQUETE A LA BDD
        // L'ID DANS LA BDD EST UN OBJET DONC JE DOIS FOURNIR UN ID AU FORMAT OBJET POUR RÉCUPÉRER LE PRODUIT
        $productBsonDocumentObject = $cnx->findOne(['_id' => new MongoDB\BSON\ObjectId($id)], "product");
        // CONVERTIR LE RESULTAT RÉCUPÉRÉ (QUI EST UN OBJET BSON) EN FORMAT OBJET AVEC LA STRUCTURE PRODUCT
        // DONC JE DOIS DÉSERIALISER LE RESULTAT AVEC UNE METHODE PREDEFINI DANS L'API MONGO

        // CONVERTIR LE BSON EN STRING
        $productString = MongoDB\BSON\fromPHP($productBsonDocumentObject);
        // dd($productString);

        // ON COMMUNIQUE $productString A LA FONCTION toPHP() QUI PREND 2 PARAMÈTRES
        // LE PREMIER : L'OBJET BSON TRANSFORMÉ EN STRING
        // LE DEUXIÈME : LA CLASSE CORRESPONDANT À L'OBJET COURANT (ICI 'Product')
        // ET CA NOUS RETOURNE UN OBJET PHP
        $productObject = MongoDB\BSON\toPHP($productString, ['root' => 'Product']);
        dd($productObject);

        return $productObject;
    }

    // RECUPERER TOUS LES PRODUITS (MONGO DB)
    public static function getAllProducts()
    {
        $cnx = new Connexion();

        // RÉCUPÉRATION APRÈS LA REQUETE A LA BDD
        // LA COLLECTION $collection (TABLE EN SQL) 
        $arrayProductsBsonDocumentObject = $cnx->findAll('product');
        // CONVERTIR LE RESULTAT RÉCUPÉRÉ (QUI EST UN OBJET BSON) EN FORMAT OBJET AVEC LA STRUCTURE PRODUCT
        // DONC JE DOIS DÉSERIALISER LE RESULTAT AVEC UNE METHODE PREDEFINI DANS L'API MONGO
        //dd($arrayProductsBsonDocumentObject);

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
        // dd($productsObjects);
        return $productsObjects;
    }

    // MODIFIER UN PRODUIT (MONGO DB) A FAIRE
    public function update()
    {
        $cnx = new Connexion();
        // A MODIFIER...
        // $cnx->querySQL(
        //     "UPDATE  product SET name= ?, description=?, price=?, quantity=?, picture_url=? WHERE id=?",
        //     [
        //         $this->name,
        //         $this->description,
        //         $this->price,
        //         $this->quantity,
        //         $this->picture_url,
        //         $this->id
        //     ]
        // );
    }

    // // RÉCUPÉRER UN SEUL PRODUIT (BDD MYSQL)
    // public static function getProductById($id)
    // {
    //     $cnx = new Connexion();
        
    //     $product = $cnx->getOne("SELECT * FROM product WHERE id=?", [$id], 'Product');

    //     return $product;
    // }

    // RÉCUPERER LA LISTE DE TOUS LES PRODUITS (BDD MYSQL)
    // public static function getAllProducts()
    // {
    //     $cnx = new Connexion();
    //     $products = $cnx->getMany("SELECT * FROM product", "Product");

    //     return $products;
    // }

    // // MODIFIER UN PRODUIT (BDD MYSQL)
    // public function update()
    // {
    //     $cnx = new Connexion();
    //     $cnx->querySQL(
    //         "UPDATE  product SET name= ?, description=?, price=?, quantity=?, picture_url=? WHERE id=?",
    //         [
    //             $this->name,
    //             $this->description,
    //             $this->price,
    //             $this->quantity,
    //             $this->picture_url,
    //             $this->id
    //         ]
    //         );  
    // }


}