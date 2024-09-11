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
    
    .form {
          background-color: white;
          padding: 50px;
          border-radius: 10px;
          box-shadow: 2px 2px 10px gray;
          text-align: center;
    }
    
    .header {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 50px;
    }

    table {
        width: 80%;
        background-color: white;
        padding: 50px;
        border-radius: 10px;
        box-shadow: 2px 2px 10px gray;
        text-align: center;
        border-collapse: collapse;
    }

    th, td {
        border: 1px solid black;
        background-color: white;
        border-radius: 10px;
        padding: 10px;
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
    <?php if(check_verified()):?>
        <!-- Inizio area riservata -->
        <?php
        $connessione = new mysqli("localhost", "id20210339_itivoltabiblioteca", "password", "id20210339_dbbiblioteca");

        // Ottengo l'email dell'utente dalla sessione
        $userEmail = $_SESSION['USER']->email;

        // Ottengo le richieste dell'utente con i dettagli del libro
        $sql = "SELECT r.IDRichiesta, r.DataRitiro, r.DataConsegna, r.Stato, r.IDLibro, l.Titolo
                FROM Richieste AS r
                INNER JOIN Libri AS l ON r.IDLibro = l.IDLibro
                INNER JOIN Utente AS u ON r.Utente = u.id
                WHERE u.email = '$userEmail'";

        $result = $connessione->query($sql);

        if ($result and $result->num_rows > 0) {
            echo '<h2>Prenotazioni Utente</h2>';
            echo '<table>
                    <tr>
                        <th>Data di Ritiro</th>
                        <th>Data di Consegna</th>
                        <th>Stato</th>
                        <th>Titolo Libro</th>
                        <th></th>
                        <th></th>
                    </tr>';

            while ($row = $result->fetch_assoc()) {
                $requestID = $row['IDRichiesta'];
                $dataRitiro = $row['DataRitiro'];
                $dataConsegna = $row['DataConsegna'];
                $stato = $row['Stato'];
                $titoloLibro = $row['Titolo'];
                $IDLibro = $row['IDLibro'];

                $statoText = '';
                $changeDatesAction = '';
                $cancelAction = '';

                switch ($stato) {
                    case -2:
                        $statoText = 'Annullata';
                        $cancelAction = '<form action="elimina.php" method="POST">
                                            <input type="hidden" name="request_id" value="' . $requestID . '">
                                            <input type="hidden" name="select_id" value="' . $IDLibro . '">
                                            <input type="submit" value="Elimina"></td>
                                        </form>';
                        break;
                    case -1:
                        $statoText = 'Rifiutata';
                        $cancelAction = '<form action="elimina.php" method="POST">
                                            <input type="hidden" name="request_id" value="' . $requestID . '">
                                            <input type="hidden" name="select_id" value="' . $IDLibro . '">
                                            <input type="submit" value="Elimina"></td>
                                        </form>';
                        break;
                    case 0:
                        $statoText = 'In attesa';
                        $cancelAction = '<form action="annulla.php" method="POST">
                                            <input type="hidden" name="request_id" value="' . $requestID . '">
                                            <input type="hidden" name="select_id" value="' . $IDLibro . '">
                                            <input type="submit" value="Annulla"></td>
                                        </form>';
                        
                        break;
                    case 1:
                        $statoText = 'Approvata';
                        $cancelAction = '<form action="annulla.php" method="POST">
                                            <input type="hidden" name="request_id" value="' . $requestID . '">
                                            <input type="hidden" name="select_id" value="' . $IDLibro . '">
                                            <input type="submit" value="Annulla"></td>
                                        </form>';
                        break;
                    default:
                        $statoText = 'Sconosciuto';
                        break;
                }
                
                

                echo '<tr>
                        <td>' . $dataRitiro . '</td>
                        <td>' . $dataConsegna . '</td>
                        <td>' . $statoText . '</td>
                        <td>' . $titoloLibro . '</td>
                        <td>' . $changeDatesAction . '</td>
                        <td>' . $cancelAction . '</td>
                    </tr>';
            }

            echo '</table>';
        } else {
            echo '<b><h1>Nessuna prenotazione trovata per l\'utente</h1></b>';
        }

        $connessione->close();
        ?>
        <!-- Fine area riservata -->
    <?php else: ?>
        <br><br>
        <a href="/Mail_Verify/verify.php">
            <button>Verifica profilo</button>
        </a>
        <br> <br>
    <?php endif; ?>
<?php endif; ?>

</body>
</html>
