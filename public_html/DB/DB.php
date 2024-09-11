<?php

    //apro la connessione
    $connessione = new mysqli("localhost", "id20210339_itivoltabiblioteca", "password", "id20210339_dbbiblioteca");
    if(!$connessione)
    {
        die("Errore di connessione: ". $connessione->connect_error);
    }
    
    //recupero i dati di cui ho bisogno 
    $Data_Cat = date('Y/m/d');
    $Titolo = $connessione->real_escape_string($_REQUEST['Titolo']);
    $Autori = $connessione->real_escape_string($_REQUEST['Autori']);
    $Casa_Editrice = $connessione->real_escape_string($_REQUEST['Casa_Editrice']);
    $Luogo_Stampa = $connessione->real_escape_string($_REQUEST['Luogo_Stampa']);
    $Anno_Pubblicazione = $connessione->real_escape_string($_REQUEST['Anno_Pubblicazione']);
    $ISBN = $connessione->real_escape_string($_REQUEST['ISBN']);
    $Lingua = $connessione->real_escape_string($_REQUEST['Lingua']);
    $Genere = $connessione->real_escape_string($_REQUEST['Genere']);
    $Genere1 = $connessione->real_escape_string($_REQUEST['Genere1']);
    $Genere2 = $connessione->real_escape_string($_REQUEST['Genere2']);
    $Volumi = $connessione->real_escape_string($_REQUEST['Volumi']);
    $Copie = $connessione->real_escape_string($_REQUEST['Copie']);
    
    //predispongo il contatore per l'iterazione
    $i = 0;
    
    //inserisco tutte le copie
    while ($i < $Copie)
    {
        //ricerco il libro con il campo Numero più grande
        $IDLibro = "";
        $sql = "SELECT Numero FROM Libri ORDER BY Numero DESC";
        
        if($result = $connessione->query($sql))
        {
            //se lo trovo lo assegno ad una variabile e lo aumento di 1
            if($result->num_rows > 0)
            {
                $Num = $result->fetch_array();
                $Numero = $Num['Numero'] + 1;
            }
            
            //altrimenti significa che non ci sono libri e questo sarà il primo libro
            else
            {
                $Numero = 1;
            }
            
            //genero l'id del libro
            $IDLibro .= strval($Numero);
            $IDLibro .= substr($Autori,0,1);
            $IDLibro .= substr($Titolo,0,1);
            $IDLibro .= strval(date('y'));
        }
        else
        {
            echo "Errore durante l'inserimento " . $connessione->error;
        }
        
        //eseguo la concatenazione dei vari campi genere recuperati dal database in modo da poterli inserire in un campo singolo, controllo che i campi siano compilati e diversi tra loro
        if($Genere1 != "" && $Genere1 != $Genere)
        {
            if($Genere2 != "" && $Genere2 != $Genere && $Genere2 != $Genere)
            {
                $Genere .= ", ";
                $Genere .= $Genere1;
                $Genere .= ", ";
                $Genere .= $Genere2;
            }
            else
            {
                $Genere .= ", ";
                $Genere .= $Genere1;
            }
        }
        else if($Genere2 != "" && $Genere2 != $Genere)
        {
            $Genere .= ", ";
            $Genere .= $Genere2;
        }
        
        //ora che ho tutti i dati del libro eseguo la query di inserimento
        $sql = "INSERT INTO Libri (IDLibro, Titolo, Autori, Casa_Editrice, Luogo_stampa, Anno_Pubblicazione, ISBN, Lingua, Genere, DataCat, Volumi, Numero) VALUES('$IDLibro', '$Titolo', '$Autori', '$Casa_Editrice', '$Luogo_Stampa', '$Anno_Pubblicazione', '$ISBN', '$Lingua', '$Genere', '$Data_Cat', '$Volumi', '$Numero')";
        
        if($connessione->query($sql))
        {
            $data = array($IDLibro, $Titolo, $Autori, $Casa_Editrice, $Luogo_Stampa, $Anno_Pubblicazione, $ISBN, $Lingua, $Genere, $Data_Cat, $Volumi, $Numero);
            $file = fopen("Back-up_Libri.csv", "a");
            fputcsv($file, $data);
            fclose($file);
        }
        else
            echo "Errore durante l'inserimento " . $connessione->error;
        $i += 1;
    }
    //chiudo la connessione e stampo i dati del libro inserito per debug
    $connessione->close();
    echo "$IDLibro, $Titolo, $Autori, $Casa_Editrice, $Luogo_Stampa, $Anno_Pubblicazione, $ISBN, $Lingua, $Genere, $Data_Cat, $Volumi, $Numero";
?>

<html>
    <body>
        <!--tasto per tornare alla caatalogazione-->
        <form name="torna indietro" action="../Catalogazione/Catalogazione.php" method="post">
            <input type="hidden" name="torna indietro" value="Torna alla Catalogazione"/>
            <input type="submit" value="Torna alla Catalogazione"/>
        </form>
    </body>