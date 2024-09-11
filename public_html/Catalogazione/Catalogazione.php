<?php 
    session_start(); 
    //se la sessione non è attiva riporto l'utente al login per la catalogazione
    if (!isset($_SESSION['login'])) { 
        header("Location: LogCatalogazione.php"); 
    }
?>
<html>
    <!-- configurazione -->
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" media="screen" href="style.css"/>
    </head>
    <right>
        <body>
            <!--tasto logout-->
            <form style="position: fixed; top: 0; right: 10;" name="logout" action="LogCatalogazione.php" method="post">
                <input type="hidden" name="logout" value="esci"/>
                <input type="submit" value="Logout"/>
            </form>
        </body>
    </right>
    <center><head><h1>Catalogazione</h1></head></center>
    <center>
        <Title>Catalogazione</Title>
            <body>
                <!--form inserimento-->
                <form method="post" action="../DB/DB.php">
                    <table>
                        <tr>
                            <th>Autori (COGNOME nome, COGNOME nome,...)</th>
                            <th>Titolo</th>
                            <th>Casa Editrice</th>
                            <th>Luogo di Stampa</th>
                        </tr>
                        <tr>
                            <td><input name="Autori" type="text" id="autori" size ="50"></td>
                            <td><input name="Titolo" type="text" id="titolo"></td>
                            <td><input name="Casa_Editrice" type="text" id="casa_editrice"></td>
                            <td><input name="Luogo_Stampa" type="text" id="luogo_di_stampa"></td>
                        </tr>
                        <tr>
                            <th>Anno di Pubblicazione</th>
                            <th>ISBN</th>
                            <th>Lingua</th>
                            <th>Volumi</th>
                        </tr>
                        <tr>
                            <td><input name="Anno_Pubblicazione" type="number" placeholder="YYYY" id="anno_di_pubblicazione"></td>
                            <td><input name="ISBN" type="text" id="isbn"></td>
                            <td><input name="Lingua" type="text" id="lingua"></td>
                            <td><select name="Volumi" id="volumi">
                                <option>Unico
                                <option>1°
                                <option>2°
                                <option>3°
                            </select></td>
                        </tr>
                        <tr>
                            <th>Genere</th>
                            <th>Numero Copie</th>
                        </tr>
                        <tr>
                            <td>
                                <select name="Genere" id="genere">
                                    <option>Realistico
                                    <option>Sentimentale/Rosa
                                    <option>Umoristico
                                    <option>Giallo/Thriller
                                    <option>Fantastico
                                    <option>Horror
                                    <option>Fantasy
                                    <option>Fantascienza
                                    <option>Fiaba
                                    <option>Favola
                                    <option>Distopia
                                    <option>Fantastoria/Ucronia
                                    <option>Storico
                                    <option>Filosofico
                                    <option>Storia Locale
                                    <option>Narrativa
                                    <option>Autobiografia
                                    <option>Saggio
                                    <option>Raccolta
                                    <option>Divulgazione Scientifica
                                    <option>Tragedia
                                    <option>Commedia
                                    <option>Dramma
                                    <option>Satira
                                    <option>Avventura
                                    <option>Raccolta di Poesia
                                </select>
                                <select name="Genere1" id="genere1">
                                    <option value="" selected="selected" hidden="hidden">Scegli Genere
                                    <option>Realistico
                                    <option>Sentimentale/Rosa
                                    <option>Umoristico
                                    <option>Giallo/Thriller
                                    <option>Fantastico
                                    <option>Horror
                                    <option>Fantasy
                                    <option>Fantascienza
                                    <option>Fiaba
                                    <option>Favola
                                    <option>Distopia
                                    <option>Fantastoria/Ucronia
                                    <option>Storico
                                    <option>Filosofico
                                    <option>Storia Locale
                                    <option>Narrativa
                                    <option>Autobiografia
                                    <option>Saggio
                                    <option>Raccolta
                                    <option>Divulgazione Scientifica
                                    <option>Tragedia
                                    <option>Commedia
                                    <option>Dramma
                                    <option>Satira
                                    <option>Avventura
                                    <option>Raccolta di Poesia
                                </select>
                                <select name="Genere2" id="genere2">
                                    <option value="" selected="selected" hidden="hidden">Scegli Genere
                                    <option>Realistico
                                    <option>Sentimentale/Rosa
                                    <option>Umoristico
                                    <option>Giallo/Thriller
                                    <option>Fantastico
                                    <option>Horror
                                    <option>Fantasy
                                    <option>Fantascienza
                                    <option>Fiaba
                                    <option>Favola
                                    <option>Distopia
                                    <option>Fantastoria/Ucronia
                                    <option>Storico
                                    <option>Filosofico
                                    <option>Storia Locale
                                    <option>Narrativa
                                    <option>Autobiografia
                                    <option>Saggio
                                    <option>Raccolta
                                    <option>Divulgazione Scientifica
                                    <option>Tragedia
                                    <option>Commedia
                                    <option>Dramma
                                    <option>Satira
                                    <option>Avventura
                                    <option>Raccolta di Poesia
                                </select>
                            </td>
                            <td><input name="Copie" id="copie" type="number" value=1></td>
                        </tr>
                    </table>
                    <input type="submit" value="Inserisci"></td>
                </form>
                
                <?php
                
                    //apro la connessione e seleziono tutti i campi che mi interessano di tutti i libri e li inserisco in una tabella, inserisco anche 2 tabelle una per la modifica e una per l'eliminazione
                    $connessione = new mysqli("localhost", "id20210339_itivoltabiblioteca", "password", "id20210339_dbbiblioteca");
                    if(!$connessione)
                        die("Errore di connessione: " . $connessione->connect_error);
                    
                    $sql = "SELECT IDLibro, Autori, Titolo, Luogo_stampa, Anno_Pubblicazione, Casa_Editrice, Lingua, Genere, ISBN, Volumi FROM Libri ORDER BY Numero ASC";
                    
                    if($result = $connessione->query($sql))
                    {
                        echo '
                            <table>
                            <tr>
                            <th>IDLibro</th>
                            <th>Autori</th>
                            <th>Titolo</th>
                            <th>Luogo_stampa</th>
                            <th>Anno_Pubblicazione</th>
                            <th>Casa_Editrice</th>
                            <th>Genere</th>
                            <th>Lingua</th>
                            <th>ISBN</th>
                            <th>Volumi</th>
                            <th>Modifica</th>
                            <th>Elimina</th>
                            </tr>
                        ';
                         
                        if($result->num_rows > 0)
                        {
                            while($row = $result->fetch_array())
                            {
                                echo '
                                <tr>
                                <td>' . $row['IDLibro'] . '</td>
                                <td>' . $row['Autori'] . '</td>
                                <td>' . $row['Titolo'] . '</td>
                                <td>' . $row['Luogo_stampa'] . '</td>
                                <td>' . $row['Anno_Pubblicazione'] . '</td>
                                <td>' . $row['Casa_Editrice'] . '</td>
                                <td>' . $row['Genere'] . '</td>
                                <td>' . $row['Lingua'] . '</td>
                                <td>' . $row['ISBN'] . '</td>
                                <td>' . $row['Volumi'] . '</td>
                                <td> <form action="../DB/update.php" method="post">
                                    <input name="update_id" id="update_id" type="hidden" value="' . $row['IDLibro'] . '">
                                    <input type="submit" value="Modifica">
                                    </form>
                                </td>
                                <td> <form action="../DB/delete.php" method="post">
                                    <input name="id" id="id" type="hidden" value="' . $row['IDLibro'] . '">
                                    <input type="submit" value="Elimina">
                                    </form>
                                </td>
                                </tr>
                                ';
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
                
            </body>
        </center>
    </html>    