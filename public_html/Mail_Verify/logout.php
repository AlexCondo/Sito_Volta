<?php

    session_start();
    
    //se la sessione è avviata la chiudo
    if(isset($_SESSION['USER'])){
        unset($_SESSION['USER']);
    }
    
    if(isset($_SESSION['LOGGED_IN'])){
        unset($_SESSION['LOGGED_IN']);
    }
    
    //se la sessione Admin è avviata la chiudo
    if(isset($_SESSION['ADMIN'])){
        unset($_SESSION['ADMIN']);
    }
    
    if(isset($_SESSION['LOG_ADMIN'])){
        unset($_SESSION['LOG_ADMIN']);
    }
    
    
    //reinderizzo l'utente al login
    header("Location: /login.php");
    die;