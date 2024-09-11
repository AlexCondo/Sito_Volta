<?php

    //apro la connessione
    $connessione = new mysqli("localhost", "id20210339_itivoltabiblioteca", "password", "id20210339_dbbiblioteca");
    if(!$connessione)
    {
        die("Errore di connessione: ". $connessione->connect_error);
    }
    
    //predispongo le variabili per la modifica e prendo l'id dalla funzione chiamante
    $ID = $_POST['update_id'];
    $autori = "";
    $titolo = "";
    $casa_editrice = "";
    $luogo_stampa = "";
    $anno_pubblicazione = "";
    $lingua = "";
    $ISBN = "";


    //prendo i dati del libro che corrisponde all'id recuperato
    $sql = "SELECT * FROM Libri WHERE IDLibro = '$ID'";
    
    if($result = $connessione->query($sql))
    {
        //salvo i dati in una variabile
        if($row = $result->fetch_array())
        {
            $autori = $row['Autori'];
            $titolo = $row['Titolo'];
            $casa_editrice = $row['Casa_Editrice'];
            $luogo_stampa = $row['Luogo_stampa'];
            $anno_pubblicazione = $row['Anno_Pubblicazione'];
            $genere = $row['Genere'];
            $lingua = $row['Lingua'];
            $ISBN = $row['ISBN'];
            $volumi = $row['Volumi'];
        }
        else
        {
            die("Nessun libro" . $connessione->error);
        }
    }
    else
    {
        die("Errore query " . $connessione->error);
    }
    
    $connessione->close();
    
    //creo un form con i dati vecchi del libro come dati di default e permetto all'utente di cambiarli
    echo'
    <html>
        <head>
            <meta charset="utf-8"/>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="stylesheet" type="text/css" media="screen" href="../Catalogazione/style.css"/>
        </head>
        <body>
            <form method = "post" action ="DBupdate.php">
                <table>
                    <input name="id" id="id" type="hidden" value="' . $ID . '">
                    <tr>
                        <th>Autori (COGNOME nome, COGNOME nome,...)</th>
                        <th>Titolo</th>
                        <th>Casa Editrice</th>
                        <th>Luogo di Stampa</th>
                    </tr>
                    <tr>
                        <td><input name="nuovo_Autori" type="text" id="nuovo_autori" size ="50" value="' . $autori . '"></td>
                        <td><input name="nuovo_Titolo" type="text" id="nuovo_titolo" value="' . $titolo . '"></td>
                        <td><input name="nuovo_Casa_Editrice" type="text" id="nuovo_casa_editrice" value="' . $casa_editrice . '"></td>
                        <td><input name="nuovo_Luogo_Stampa" type="text" id="nuovo_luogo_di_stampa" value="' . $luogo_stampa . '"></td>
                    </tr>
                    <tr>
                        <th>Anno di Pubblicazione</th>
                        <th>ISBN</th>
                        <th>Lingua</th>
                        <th>Volumi</th>
                    </tr>
                    <tr>
                        <td><input name="nuovo_Anno_Pubblicazione" type="number" placeholder="YYYY" id="nuovo_anno_di_pubblicazione" value="' . $anno_pubblicazione . '"></td>
                        <td><input name="nuovo_ISBN" type="text" id="nuovo_isbn" value="' . $ISBN . '"></td>
                        <td><input name="nuovo_Lingua" type="text" id="nuovo_lingua" value="' . $lingua . '"></td>
                        <td><select name="nuovo_Volumi" id="nuovo_volumi" value="' . $volumi . '">
                            <option value="" selected="selected" hidden="hidden">Scegli Volumi
                            <option>Unico</option>
                            <option>1°</option>
                            <option>2°</option>
                            <option>3°</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>Genere</th>
                    </tr>
                    <tr>
                        <td>
                            <select name="nuovo_Genere" id="genere">
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
                            <select name="nuovo_Genere1" id="genere1">
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
                            <select name="nuovo_Genere2" id="genere2">
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
                    </tr>
                </table>
                <h1>Genere e Volumi vanno inseriti manualmente, in caso contrario la modifica non verrà eseguita.</h1>
                <input type="submit" value="Modifica"></td>
            </form>
            <form name="torna indietro" action="../Catalogazione/Catalogazione.php" method="post">
                <input type="hidden" name="torna indietro" value="Torna alla Catalogazione"/>
                <input type="submit" value="Torna alla Catalogazione"/>
            </form>
        </body>
    </html>
    ';
?>