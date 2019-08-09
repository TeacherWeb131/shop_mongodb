<?php



class User implements MongoDB\BSON\Serializable, MongoDB\BSON\Unserializable
{
    // proprietés
    
    private $_id;
    private $first_name;
    private $last_name;
    private $email;
    private $created_at;
    private $password;
    private $admin;

    // (MONGODB)
    // ON SERIALISE LES PROPRIÉTÉS (MONGODB)
    public function bsonSerialize()
    {
        return [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'created_at' => new \MongoDB\BSON\UTCDateTime(new DateTime()),
            'password' => password_hash($this->password, PASSWORD_BCRYPT),
            'admin' => $this->admin
        ];
    }

    // (MONGODB)
    // DANS LES METHODES DE RÉCUPÉRATION DE DONNÉES DE LA BDD, ON RÉCUPÈRE UN TABLEAU D'OBJETS BSON DONC
    // ON A BESOIN DE DESERIALISER LES OBJETS CONTENU DANS CE TABLEAU (ARRAY)
    // CETTE METHODE EST À IMPLÉMENTER DE MANIÈRE OBLIGATOIRE
    // CAR ON A IMPLÉMENTÉ L'INTERFACE 'MongoDB\BSON\Unserializable'
    // (CETTE MÉTHODE EST UTILISÉ DE MANIÈRE TRANSPARENTE PAR MONGO 
    // AVEC LA METHODE 'findOne(), find()' POUR DESERIALISER LE RETOUR DE NOTRE FUNCTION DE RECUPERATION
    // DE DONNÉE COMME getUserById($id) QUI NOUS RENVOIE UN OBJET BSON
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
    
    // getter et setter

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Get the value of first_name
     */ 
    public function getFirst_name()
    {
        return $this->first_name;
    }

    /**
     * Set the value of first_name
     *
     * @return  self
     */ 
    public function setFirst_name($first_name)
    {
        $this->first_name = $first_name;

        return $this;
    }

    /**
     * Get the value of last_name
     */ 
    public function getLast_name()
    {
        return $this->last_name;
    }

    /**
     * Set the value of last_name
     *
     * @return  self
     */ 
    public function setLast_name($last_name)
    {
        $this->last_name = $last_name;

        return $this;
    }

    /**
     * Get the value of email
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */ 
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
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



    // méthode d'insertion dans la base de données


    // méthode de récupération de tous les utilisateurs



    // méthode de récupération d'un seul utilisateur

    /**
     * Get the value of password
     */ 
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */ 
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of admin
     */ 
    public function getAdmin()
    {
        return $this->admin;
    }

    /**
     * Set the value of admin
     *
     * @return  self
     */ 
    public function setAdmin($admin)
    {
        $this->admin = $admin;

        return $this;
    }

    public function isValid()
    {
        // 1 il a bien rempli tous les champs


        // 2 l'adresse mail n'existe pas dans notre BDD

       
        // permet de valider le contenu récupérer du formulaire


        // son retour est boolean
        return true;
    }

    // FONCTION D'INSERTION POUR MONGODB (OK)
    public function save()
    {
        $cnx = new Connexion();
        $cnx->addToDb("user", $this);
    }

    // FONCTION DE RÉCUPERATION POUR MONGODB (A CORRIGER)
    public static function getUserByEmail($email)
    {
        $cnx = new Connexion();

        // RÉCUPÉRATION APRÈS LA REQUETE A LA BDD
        $oneUserBsonDocumentObject = $cnx->findOne(["email" => $email], "user");

        // CONVERTIR LE BSON EN STRING
        $userString = MongoDB\BSON\fromPHP($oneUserBsonDocumentObject);

        // ON COMMUNIQUE $userString A LA FONCTION toPHP() QUI PREND 2 PARAMÈTRES
        // LE PREMIER : L'OBJET BSON TRANSFORMÉ EN STRING
        // LE DEUXIÈME : LA CLASSE CORRESPONDANT À L'OBJET COURANT (ICI 'User')
        // ET CA NOUS RETOURNE UN OBJET PHP
        $userObject = MongoDB\BSON\toPHP($userString, ['root' => 'User']);
        return $userObject;
    }

    // FONCTION DE RÉCUPERATION POUR MONGODB
    public static function getUserById($id)
    {
        $cnx = new Connexion();

        // RÉCUPÉRATION APRÈS LA REQUETE A LA BDD
        // L'ID DANS LA BDD EST UN OBJET DONC JE DOIS FOURNIR UN ID AU FORMAT OBJET POUR RÉCUPÉRER LE PRODUIT
        $oneUserBsonDocumentObject = $cnx->findOne(["_id" =>  new MongoDB\BSON\ObjectId($id)], "user");
        // dd($oneUserBsonDocumentObject);
        // CONVERTIR LE BSON EN STRING
        $userString = MongoDB\BSON\fromPHP($oneUserBsonDocumentObject);

        // ON COMMUNIQUE $userString A LA FONCTION toPHP() QUI PREND 2 PARAMÈTRES
        // LE PREMIER : L'OBJET BSON TRANSFORMÉ EN STRING
        // LE DEUXIÈME : LA CLASSE CORRESPONDANT À L'OBJET COURANT (ICI 'User')
        // ET CA NOUS RETOURNE UN OBJET PHP
        $userObject = MongoDB\BSON\toPHP($userString, ['root' => 'User']);
        // dd($userObject);

        return $userObject;
    }

    
    // public function save()
    // {
    //     $cnx = new Connexion();
    //     $cnx->querySQL(
    //         "INSERT INTO user (first_name, last_name, email,created_at, password, admin) VALUES (?,?,?,?,?,?)", 
    //         [
    //             $this->first_name,
    //             $this->last_name,
    //             $this->email,
    //             date("Y-m-d H:i:s"),
    //             password_hash($this->password, PASSWORD_BCRYPT),
    //             $this->admin

    //         ]
    //         );
    // }

    // public static function getUserByEmail($email)
    // {
    //     $cnx = new Connexion();

    //     $user = $cnx->getOne("SELECT * FROM user WHERE email = ?", [$email], "User");

    //     return $user;
    // }
    // public static function getUserById($id)
    // {
    //     $cnx = new Connexion();

    //     $user = $cnx->getOne("SELECT * FROM user WHERE id = ?", [$id], "User");

    //     return $user;
    // }
}
