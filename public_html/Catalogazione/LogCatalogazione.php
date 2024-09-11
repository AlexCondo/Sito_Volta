<?php 
    session_start(); 
    $password="password_catalogazione";    /* inserire su questa riga la password voluta */
    
    /*controllo se la sessione di logout è attiva, in caso faccio il logout altrimenti apro direttametne Catalogazione.php*/
    if (isset($_SESSION['login'])) { 
        if (isset($_POST['logout'])) {
            unset($_SESSION['login']);
            $messaggio = "Logout effettuato con successo! Arrivederci!";
        } else {
            header("Location: Catalogazione.php"); 
        }
    /*controllo la password*/
    } else {
        if (isset($_POST['password'])) {
            if ($_POST['password'] == $password) {
                $_SESSION['login'] = "verificata";
                header("Location: Catalogazione.php");
            } else {
                $messaggio = "Errore: password non corretta!";
            }
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
        <img src="../logo.png" style="display: block; margin: 0 auto; margin-left: -20%;">
        </div>
        
        <h2 style="font-family: arial">LOGIN CATALOGAZIONE</h2>
        
        <br>
        
        <?php include('../header.php')?>
        
        <br>
        
        <form class="form" action="LogCatalogazione.php" method="post" >
            <label for="password" style="font-family: arial">Password:</label>
            <input type="password" id="password" placeholder="Inserisci password" name="password" required>
            <br>
            
            <input type="submit" value="Login" style="font-family: arial">
        </form>
        <?php 
            if(isset($messaggio)) {
                echo $messaggio;
                unset($messaggio);
            } 
        ?>
    </body>
</html>