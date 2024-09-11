<?php
    //controllo se è stato effettuato il login
    require('../Mail_Verify/functions.php');
    
    if (!check_login_admin(false)){
        check_login();
    }
    
    $connessione = new mysqli("localhost", "id20210339_itivoltabiblioteca", "password", "id20210339_dbbiblioteca");
    
    $IDRichiesta = $_POST['request_id'];
    // aggiorna la prenotazione nel database
    $sql = "DELETE FROM Richieste WHERE IDRichiesta = '$IDRichiesta'";
    if ($connessione ->query($sql)){
    }
    else{
        echo "Errore DB $sql. " . $connessione->error;
    }
    $connessione->close();
    
    header("Location: prenotazioni.php");
?>