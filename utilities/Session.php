<?php


class Session
{
    public function __construct()
    {
        if(session_status() == PHP_SESSION_NONE)
        {
            session_start();
        }
    }

    public function getLoggedUserId()
    {
        return $_SESSION['id_user'];
    }
    public function onUserLogin($user)
    {
        $_SESSION = [
            'logged' => true,
            'id_user' => $user->getId(),
            'first_name' => $user->getFirst_Name(),
            'last_name' => $user->getLast_Name(),
            'admin' => $user->getAdmin()
        ];
    }

    public function isLogged()
    {
        
        if(isset($_SESSION['logged']) &&  $_SESSION['logged'])
        {
            
            return true;
        }

        return false;
    }

    public function isAdmin()
    {

        if (isset($_SESSION['admin']) &&  $_SESSION['admin'] == 1 ) {
            return true;
        }

        return false;
    }


    public function destroy()
    {
        $_SESSION = [];
        session_destroy();
    }
}