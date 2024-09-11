<?php

    //importo il file con le funzioni e quello con la funzione per l'invio delle email
    require('functions.php');
    require('mail.php');
    
    //controllo se l'utente è verificato
    check_verified();

    $errors = array();
    
    //se l'utente non è verificato elimino eventuali codici generati precedentemente e genero un codice che invio tramite email
    if($_SERVER['REQUEST_METHOD'] == "GET" && !check_verified())
    {
        
        $query = "DELETE FROM Verifica WHERE email = :email";
        $mail = array();
        $mail['email'] = $_SESSION['USER']->email;
        database_Signup($query, $mail);
        
        $vars['code'] = Code();
        
        $vars['expires'] = (time() + (60 * 2));
        $vars['email'] = $_SESSION['USER']->email;
        
        $query = "INSERT INTO Verifica (code, expires, email) VALUES (:code, :expires, :email)";
        
        database_Signup($query, $vars);
        
        $message = "Il tuo codice di verifica è ". $vars['code'];
        $subject = "Verifica email";
        $recipient = $vars['email'];
        
        send_mail($recipient, $subject, $message);
    }
    //quando viene inserito il codice controllo che sia corretto e in caso verifico la email
    if($_SERVER['REQUEST_METHOD'] == "POST")
    {
        if(!check_verified())
        {
            $query = "SELECT * FROM Verifica WHERE code = :code && email = :email";
            $vars = array();
            $vars['email'] = $_SESSION['USER']->email;
            $vars['code'] = $_POST['code'];
            
            $row = database_run($query, $vars);
            
            if(is_array($row))
            {
                $row = $row[0];
                $time = time();
                if($row->expires > $time)
                {
                    $id = $_SESSION['USER']->id;
                    $query = "UPDATE Utente SET email_verified = email WHERE id = '$id' LIMIT 1";
                    database_Signup($query);
                    
                    header("Location: /index.php");
                    die;
                }else
                    echo "codice scaduto";
                }else
                    echo "Codice errato";
        }else
            echo "Già Verificato";
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
        <title>Verifica</title>
    </head>
    <body>
        
        <div class="header">
            <img src="../logo.png" style="display: block; margin: 0 auto; margin-left: -20%;">
        </div>
        
        <h1>Verifica</h1>
        <?php include "../header.php";?>
        
        <br>
        <form method="post" class="form">
            <br>
            <label for="email" style="font-family: arial">Inserire codice di verifica ricevuto via mail:</label>
            
            <input type ="text" name="code" placeholder="Codice" required>
            <br> <input type="submit" value="Verifica" style="font-family: arial">
        </form>
        
        <div>
            <?php if(count($errors) > 0):?>
                <?php foreach ($errors as $error):?>
                    <?= $error?> <br>
                <?php endforeach;?>
            <?php endif;?>
            
        </div>
        
    </body>
</html>