<?php

// Controlla se è stato effettuato il login
require('../Mail_Verify/functions.php');
check_login();

?>

<html lang="it">
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

    .form select,
    .form input[type="email"],
    .form input[type="password"],
    .form input[type="password"],
    .form input[type="text"]{
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

    table{
        width: 80%;
        background-color: white;
        padding: 50px;
        border-radius: 10px;
        box-shadow: 2px 2px 10px gray;
        text-align: center;
        border-collapse: collapse;
    }

    th, td{
        border: 1px solid black;
        background-color: white;
        border-radius: 10px;
        padding: 5px;
        text-align: center;
    }
    
    button {
           text-align: center;
            margin: 10px 0;
            padding: 10px 16px; 
    }
</style>

<right>
    <body>
        <!-- tasto Logout -->
        <a href="../Mail_Verify/logout.php" style="position: fixed; top: 10; right: 10;">
            <button>Logout</button>
        </a>
    </body>
</right>

<body>

<div class="header">
    <img src="../logo.png" style="display: block; margin: 0 auto; margin-left: -20%;">
</div>

<?php include('../header.php')?>


<?php if(check_login(false)):?>
    <!-- Controllo se l'utente è loggato; in caso contrario, verrà reindirizzato alla pagina di login -->
    <?php if(!check_verified()):?>
        <!-- Controllo se l'account è verificato; in caso contrario, visualizzo il pulsante di verifica -->
        <br><br>
        <a href="/Mail_Verify/verify.php">
            <button>Verifica profilo</button>
        </a>
    <?php endif;?>
    <br> <br>

<?php endif;?>
<?php if(check_verified()):?>
    <!-- Inizio area riservata -->

    <?php
    // Stabilisco la connessione al database
    $connessione = new mysqli("localhost", "id20210339_itivoltabiblioteca", "password", "id20210339_dbbiblioteca");

    // Verifico se il form è stato inviato e salvo i dati
    $libro = isset($_POST['Libro']) ? $_POST['Libro'] : '';
    $criterio = isset($_POST['Criterio']) ? $_POST['Criterio'] : '1';

    $sql = "SELECT Titolo, Autori, Casa_Editrice, Anno_Pubblicazione, Genere, Lingua, Volumi, IDLibro FROM Libri WHERE Titolo LIKE '%$libro%' ORDER BY Titolo ASC";

    // Stampo i libri in una tabella
    if($result = $connessione->query($sql))
    {
        echo '<table>
                      <tr>
                        <th>IDLibro</th>
                        <th>Titolo</th>
                        <th>Autori</th>
                        <th>Anno_Pubblicazione</th>
                        <th>Casa_Editrice</th>
                        <th>Genere</th>
                        <th>Lingua</th>
                        <th>Volumi</th>
                        <th>Seleziona</th>
                      </tr>';
        if($result->num_rows > 0)
        {
            while($row = $result->fetch_array())
            {
                echo '<tr>
                                <td>' . $row['IDLibro'] . '</td>
                                <td>' . $row['Titolo'] . '</td>
                                <td>' . $row['Autori'] . '</td>
                                <td>' . $row['Anno_Pubblicazione'] . '</td>
                                <td>' . $row['Casa_Editrice'] . '</td>
                                <td>' . $row['Genere'] . '</td>
                                <td>' . $row['Lingua'] . '</td>
                                <td>' . $row['Volumi'] . '</td>
                                <td>
                                      <form action="SelezioneDate.php" method="post">
                                            <input type="hidden" id="Ritiroid" name="Ritiroid" value="' .NULL.'">
                                            <input type="hidden" id="Consegnaid" name="Consegnaid" value="'.NULL.'">
                                            <input name="select_id" id="select_id" type="hidden" value="' . $row['IDLibro'] . '">
                                            <input type="submit" value="Seleziona">
                                      </form>
                                </td>
                              </tr>';
            }
            echo "</table>";

        }
        else
            echo '<b><h1>Nessun libro nel catalogo</h1></b>';
    }
    else
        echo "Errore DB $sql. " . $connessione->error;

    $connessione->close();
    ?>
<?php endif;?>
<!-- Fine area riservata -->
</body>
</html>
