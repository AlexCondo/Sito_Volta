<?php

    require "Mail_Verify/functions.php";
    
    $errors = array();
    
    
    //se non è stato effettuato un logout non permetto di accedere alla schermata di registrazione
    if(check_login(false)){
        header("Location: index.php");
        die;
    }
    elseif (check_login_admin(false)){
        header("Location: LogAdmin.php");
        die;
    }
    
    //qunado l'utente inserisce i dati chiamo la funzione di registrazione
    if($_SERVER['REQUEST_METHOD'] == "POST")
    {
        $errors = signup($_POST);
        
        if(count($errors) == 0)
        {
            header("Location: login.php");
            die;
        }
    }
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
      </style>
      <title>Sign-Up</title>
    </head>
    
    <body>
        <div class="header">
            <img src="logo.png" style="display: block; margin: 0 auto; margin-left: -20%;">
        </div>
        
        <h2 style="font-family: arial">REGISTRAZIONE</h2>
        
        <br>
        
        <?php include('header.php')?>
        
        <br>
        
        <form class="form" method="POST">
            <label for="email" style="font-family: arial">E-mail:</label>
            <input type="email" placeholder="E-mail" name="email" required>
            
            <br>
            
            <label for="password" style="font-family: arial">Password:</label>
            <input type="password" id="password" placeholder="Inserisci password" name="password" required>
            
            <br>
            
            <label for="confirm_password" style="font-family: arial">Conferma Password:</label>
            <input type="password" id="password2" placeholder="Conferma password" name="password2" required>
            
            <br>
            
            <input type="submit" value="Registrati" style="font-family: arial">
            
        </form>
        
        <div>
            <!-- stampo gli errori -->
            <?php if(count($errors) > 0):?>
                <?php foreach ($errors as $error):?>
                    <?= $error?> <br>
                <?php endforeach;?>
            <?php endif;?>
            
        </div>
        
    </body>
</html>
