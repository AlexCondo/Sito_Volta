<?php

 //controllo se è stato effettuato il login
    require('Mail_Verify/functions.php');
    check_login_admin();
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
        <title>Admin</title>
    </head>
    <right>
        <body>
            <!-- tasto Logout -->
            <a href="Mail_Verify/logout.php" style="position: fixed; top: 10; right: 10;">
                <button>Logout</button>
            </a>
        </body>
    </right>
    <body>
        
        <div class="header">
            <img src="logo.png" style="display: block; margin: 0 auto; margin-left: -20%;">
        </div>
        
        <?php include('header.php')?>
        
        <?php if(check_login_admin(false)):?>
        
            <!-- Controllo se l'utente è loggato; in caso contrario, verrà reindirizzato alla pagina di login -->
            <?php if(check_verified_admin()):?>
            
                <!-- Inizio area riservata -->
                <?php
                
                $connessione = new mysqli("localhost", "id20210339_itivoltabiblioteca", "N@eDd9!R4tY9", "id20210339_dbbiblioteca");
        
                // Ottengo le richieste dell'utente con i dettagli del libro
                $sql = "SELECT r.IDRichiesta, r.DataRitiro, r.DataConsegna, r.Stato, r.IDLibro, l.Titolo, u.email
                        FROM Richieste AS r
                        INNER JOIN Libri AS l ON r.IDLibro = l.IDLibro
                        INNER JOIN Utente AS u ON r.Utente = u.id";
        
                $result = $connessione->query($sql);
        
                if ($result and $result->num_rows > 0) {
                    echo '<h2>Richieste prenotazione</h2>';
                    echo '<table>
                            <tr>
                                <th>Data di Ritiro</th>
                                <th>Data di Consegna</th>
                                <th>Stato</th>
                                <th>Titolo Libro</th>
                                <th>Utente</th>
                                <th>Cambia date</th>
                                <th>Rifiuta</th>
                                <th>Accetta</th>
                            </tr>';
        
                    while ($row = $result->fetch_assoc()) {
                        $requestID = $row['IDRichiesta'];
                        $dataRitiro = $row['DataRitiro'];
                        $dataConsegna = $row['DataConsegna'];
                        $stato = $row['Stato'];
                        $titoloLibro = $row['Titolo'];
                        $IDLibro = $row['IDLibro'];
                        $utente = $row['email'];
        
                        $statoText = '';
                        $changeDatesAction = '';
                        $cancelAction = '';
                        $acceptAction = '';
        
                        switch ($stato) {
                            case -2:
                                $statoText = 'Annullata';
                                $cancelAction = '<form action="Prenotazione/elimina.php" method="POST">
                                                    <input type="hidden" name="request_id" value="' . $requestID . '">
                                                    <input type="hidden" name="select_id" value="' . $IDLibro . '">
                                                    <input type="submit" value="Elimina"></td>
                                                </form>';
                                break;
                            case -1:
                                $statoText = 'Rifiutata';
                                $cancelAction = '<form action="Prenotazione/elimina.php" method="POST">
                                                    <input type="hidden" name="request_id" value="' . $requestID . '">
                                                    <input type="hidden" name="select_id" value="' . $IDLibro . '">
                                                    <input type="submit" value="Elimina"></td>
                                                </form>';
                                break;
                            case 0:
                                $statoText = 'In attesa';
                                $cancelAction = '<form action="Prenotazione/annulla.php" method="POST">
                                                <input type="hidden" name="request_id" value="' . $requestID . '">
                                                <input type="hidden" name="select_id" value="' . $IDLibro . '">
                                                <input type="submit" value="Rifiuta"></td>
                                            </form>';
                                $acceptAction = '<form action="Prenotazione/accetta.php" method="POST">
                                                <input type="hidden" name="request_id" value="' . $requestID . '">
                                                <input type="hidden" name="select_id" value="' . $IDLibro . '">
                                                <input type="submit" value="Accetta"></td>
                                            </form>';
                                $changeDatesAction = '<form action="Prenotazione/SelezioneDate.php" method="POST">
                                                <input type="hidden" name="request_id" value="' . $requestID . '">
                                                <input type="hidden" name="select_id" value="' . $IDLibro . '">
                                                <input type="submit" value="Cambia date">
                                            </form>';
                                break;
                            case 1:
                                $statoText = 'Approvata';
                                $cancelAction = '<form action="Prenotazione/elimina.php" method="POST">
                                                    <input type="hidden" name="request_id" value="' . $requestID . '">
                                                    <input type="hidden" name="select_id" value="' . $IDLibro . '">
                                                    <input type="submit" value="Elimina"></td>
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
                                <td>' . $utente . '</td>
                                <td>' . $changeDatesAction . '</td>
                                <td>' . $cancelAction . '</td>
                                <td>' . $acceptAction . '</td>
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
                <a href="Mail_Verify/verify.php">
                    <button>Verifica profilo</button>
                </a>
                <br> <br>
            <?php endif; ?>
        <?php endif; ?>

    </body>
</html>