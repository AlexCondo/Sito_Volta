<?php

    //controllo se è stato effettuato il login
    require('Mail_Verify/functions.php');
    check_login();

?>

<html>
    <head>
        <style>
        body {
          background-color: lightgray;
          display: flex;
          flex-direction: column;
          align-items: center;
        }
    
        .header {
          display: flex;
          align-items: center;
          justify-content: center;
          margin-bottom: 50px;
        }
    
        .form {
          background-color: white;
          padding: 50px;
          border-radius: 10px;
          box-shadow: 2px 2px 10px gray;
          text-align: center;
        }
    
        .form2 {
          background-color: white;
          padding: 10px;
          border-radius: 5px;
          box-shadow: 1px 1px 5px gray;
          text-align: center;
        }
    
        .form input[type="email"],
        .form input[type="password"],
        .form input[type="password"] {
          width: 100%;
          padding: 10px;
          margin-bottom: 20px;
          border-radius: 5px;
          border: none;
          box-shadow: 1px 1px 5px gray;
        }
    
        .form input[type="submit"] {
          background-color: green;
          color: white;
          padding: 10px 20px;
          border-radius: 5px;
          border-color: lightgray;
          cursor: pointer;
        }
        
        button {
            text-align: center;
            margin: 10px 0;
            padding: 10px 16px;
        }
        </style>
        <title>Home</title>
    </head>
    <right>
        <body>
            <!-- tasto Logout -->
            <a href="Mail_Verify/logout.php" style="position: fixed; top: 10px; right: 10px;">
                <button>Logout</button>
            </a>
        </body>
    </right>
    <body>
        <div class="header">
            <img src="logo.png" style="display: block; margin: 0 auto; margin-left: -20%;">
        </div>
        <?php include('header.php')?>
        <?php if(check_login(false)):?> <!--controllo se l'account è verificato, se non lo è inserisco il pulsante di verifica-->
            <?php if(!check_verified()):?>
                <br><br>
                <a href="Mail_Verify/verify.php">
                    <button>Verifica profilo</button>                
                </a>
            <?php endif;?>
            <br> <br>
            
        <?php endif;?>
        <?php if(check_verified()):?><!--inizio area riservata-->
            <?php include('Prenotazione/prenota.php') ?>
        <?php endif;?><!--fine area riservata-->
    </body>
</html>