<?php
require ('../Mail_Verify/mail.php');

//controllo se è stato effettuato il login
require('../Mail_Verify/functions.php');
//check_login();

?>

<!DOCTYPE html>
<html lang="it">
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
        button{
            background-color: green;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            border-color: lightgray;
            cursor: pointer;
        }
    </style>
    <title>Home</title>
</head>
<body>
<div class="header">
    <img src="../logo.png" style="display: block; margin: 0 auto; margin-left: -20%;">
</div>
<?php include('../header.php')?>
<h1>SPECIFICHE LIBRO:</h1>
<?php
//connessione al db
$connessione = new mysqli("localhost", "id20210339_itivoltabiblioteca", "password", "id20210339_dbbiblioteca");

//recupero id del libro e date di ritiro e consegna e nel caso della richiesta originale
$IDLibro = $_POST['select_id'];
$ritiro = $_POST['Ritiro'];
$consegna = $_POST['Consegna'];
$IDRichiesta = isset($_POST['request_id']) ? $_POST['request_id'] : NULL;

//ritiro i dati del libro
$sql = "SELECT IDLibro, Titolo, Autori, Casa_Editrice, Luogo_stampa, Anno_Pubblicazione, ISBN, Lingua, Genere, Volumi FROM Libri WHERE IDLibro= '$IDLibro'";

if($result = $connessione->query($sql)) {
    if ($result->num_rows > 0) {
        $row = $result->fetch_array(); ?>
        <table>
            <tr>
                <!-- stampo i dati del libro e le due date -->
                <h3> ID Libro: <?= $row['IDLibro'] ?></h3>
                <h3> Titolo: <?= $row['Titolo'] ?></h3>
                <h3> Autori: <?= $row['Autori'] ?></h3>
                <h3> Casa Editrice: <?= $row['Casa_Editrice'] ?></h3>
                <h3> Luogo Stampa: <?= $row['Luogo_stampa'] ?></h3>
                <h3> Anno Pubblicazione: <?= $row['Anno_Pubblicazione'] ?></h3>
                <h3> ISBN: <?= $row['ISBN'] ?></h3>
                <h3> Lingua: <?= $row['Lingua'] ?></h3>
                <h3> Genere: <?= $row['Genere'] ?></h3>
                <h3> Volumi: <?= $row['Volumi'] ?></h3>
                <h3> Data Ritiro: <?= $ritiro?></h3>
                <h3> Data Consegna: <?= $consegna?></h3>
        <?php
            if ($IDRichiesta == NULL) {
                //ricavo l'id dell'utente
                $sql = "SELECT id FROM Utente WHERE email = '".$_SESSION['USER']->email."'";
                $result = $connessione->query($sql);
                $row = $result->fetch_array();
                $user = $row['id'];

                //inserisco la nuova prenotazione nel db
                $sql = "INSERT INTO Richieste(DataRitiro, DataConsegna, Utente, Stato ,IDLibro) VALUES ('$ritiro', '$consegna', '$user', 0, '$IDLibro')";
                if ($connessione->query($sql)) {
                    echo "<h3>Richiesta inviata</h3>";
                    //invio email all'admin
                    send_mail('email@admin.com', 'Prenotazione Libro', 'Lo studente "'.$_SESSION['USER']->email.'" richiede la prenotazione del libro  "' .$row['Titolo'] . '", con id "' .$row['IDLibro']. '" nel periodo di tempo dal "'.$consegna.'" al "'.$ritiro.'"');
                } else {
                    echo "Errore DB $sql. " . $connessione->error;
                }
            }else{
                //aggiorno la prenotazione nel db
                $sql = "UPDATE Richieste SET Stato = 0, DataConsegna = '$consegna', DataRitiro = '$ritiro' WHERE IDRichiesta = '$IDRichiesta'";
                if ($connessione ->query($sql)){
                    echo "<h3>Date Cambiate</h3>";
                    //invio email all'utente(non funziona)
                    //send_mail($_SESSION['USER']->email, 'Cambiamento Date Preotazione Libro', 'Il periodo di prenotazione del libro  "' .$row['Titolo'] . '", con id "' .$row['IDLibro']. '" è stato cambiato al periodo che va dal "'.$consegna.'" al "'.$ritiro.'"');
                }else{
                    echo "Errore DB $sql. " . $connessione->error;
                }
            }
            ?>
                <a href="/index.php"><button type="button">HOME</button></a>
            </tr>
        </table>
        <?php 
    }
}else{
    echo "Errore DB $sql. " . $connessione->error;
}

$connessione->close();
?>
</body>
</html>