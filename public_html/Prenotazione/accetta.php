<?php
//controllo se è stato effettuato il login
require('../Mail_Verify/functions.php');

if (!check_login_admin(false)){
    check_login();
}

?>


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
    <title>ANNULLA</title>
</head>
<body>
<div class="header">
    <img src="../logo.png" style="display: block; margin: 0 auto; margin-left: -20%;">
</div>
<?php include('../header.php')?>
<h1>SPECIFICHE LIBRO:</h1>
<?php
// Connessione al database
$connessione = new mysqli("localhost", "id20210339_itivoltabiblioteca", "password", "id20210339_dbbiblioteca");

// Recupero ID della richiesta originale
$IDLibro = $_POST['select_id'];
$IDRichiesta = $_POST['request_id'];

// Recupero i dati del libro
$sql = "SELECT Libri.IDLibro, Titolo, Autori, Casa_Editrice, Luogo_stampa, Anno_Pubblicazione, ISBN, Lingua, Genere, Volumi, DataRitiro, DataConsegna FROM Libri JOIN Richieste ON Libri.IDLibro = Richieste.IDLibro WHERE IDRichiesta= '$IDRichiesta'";

if($result = $connessione->query($sql)) {
    if ($result->num_rows > 0) {
        $row = $result->fetch_array(); ?>
        <table>
            <tr>
                <!-- Stampo i dati del libro e le due date -->
                <h3> ID Richiesta: <?= $IDRichiesta ?></h3>
                <h3> ID Libro form: <?= $IDLibro ?></h3>
                <h3> ID Libro row: <?= $row['IDLibro'] ?></h3>
                <h3> Titolo: <?= $row['Titolo'] ?></h3>
                <h3> Autori: <?= $row['Autori'] ?></h3>
                <h3> Casa Editrice: <?= $row['Casa_Editrice'] ?></h3>
                <h3> Luogo Stampa: <?= $row['Luogo_stampa'] ?></h3>
                <h3> Anno Pubblicazione: <?= $row['Anno_Pubblicazione'] ?></h3>
                <h3> ISBN: <?= $row['ISBN'] ?></h3>
                <h3> Lingua: <?= $row['Lingua'] ?></h3>
                <h3> Genere: <?= $row['Genere'] ?></h3>
                <h3> Volumi: <?= $row['Volumi'] ?></h3>
                <?php
                // aggiorna la prenotazione nel database
                $sql = "UPDATE Richieste SET Stato = 1 WHERE IDRichiesta = '$IDRichiesta'";
                if ($connessione ->query($sql)){
                    echo "<h3>Prenotazione confermata</h3>";
                }else{
                    echo "Errore DB $sql. " . $connessione->error;
                }
                ?>
                <a href="../index.php"><button type="button">HOME</button></a>
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