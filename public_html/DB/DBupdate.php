<?php
    
    //apro la connessione
    $connessione = new mysqli("localhost", "id20210339_itivoltabiblioteca", "password", "id20210339_dbbiblioteca");
    if(!$connessione)
    {
        die("Errore di connessione: ". $connessione->connect_error);
    }
    else
    {
        //recupero l'id del libro da modificare e il suo numero
        $ID = $_POST["id"];
        $sql = "SELECT Numero FROM Libri WHERE IDLibro = '$ID'";
        
        if($result = $connessione->query($sql))
        {
            $Numero = $result->fetch_array();
            $Numero = $Numero['Numero'];
        }
        else
        {
            die("Errore, " . $connessione->error);
        }
        
        //creo le variabili di cui ho bisogno per la modifica
        $IDLibro = "";
        $Data_Cat = date('Y/m/d');
        $Titolo = $connessione->real_escape_string($_REQUEST['nuovo_Titolo']);
        $Autori = $connessione->real_escape_string($_REQUEST['nuovo_Autori']);
        $Casa_Editrice = $connessione->real_escape_string($_REQUEST['nuovo_Casa_Editrice']);
        $Luogo_Stampa = $connessione->real_escape_string($_REQUEST['nuovo_Luogo_Stampa']);
        $Anno_Pubblicazione = $connessione->real_escape_string($_REQUEST['nuovo_Anno_Pubblicazione']);
        $ISBN = $connessione->real_escape_string($_REQUEST['nuovo_ISBN']);
        $Lingua = $connessione->real_escape_string($_REQUEST['nuovo_Lingua']);
        $Genere = $connessione->real_escape_string($_REQUEST['nuovo_Genere']);
        $Genere1 = $connessione->real_escape_string($_REQUEST['nuovo_Genere1']);
        $Genere2 = $connessione->real_escape_string($_REQUEST['nuovo_Genere2']);
        $Volumi = $connessione->real_escape_string($_REQUEST['nuovo_Volumi']);
        
        //se non ci sono problemi genere e volumi devono per forza avere un valore quindi li uso per il controllo
        if($Genere != "" and $Volumi != "")
        {
            //genero l'id e aggiorno il libro
            
            $IDLibro .= strval($Numero);
            $IDLibro .= substr($Autori,0,1);
            $IDLibro .= substr($Titolo,0,1);
            $IDLibro .= strval(date('y'));
            
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
            
            $sql = "UPDATE Libri SET IDLibro = '$IDLibro', Titolo = '$Titolo', Autori= '$Autori', Casa_Editrice ='$Casa_Editrice', Luogo_stampa ='$Luogo_Stampa', Anno_Pubblicazione='$Anno_Pubblicazione', ISBN='$ISBN', Lingua='$Lingua', Genere='$Genere', DataCat='$Data_Cat', Volumi='$Volumi' WHERE IDLibro = '$ID'";
            
            //eseguo la query di modifica e chiamo la funzione per la modifica dei dati nel file CSV di backup
            if($connessione->query($sql))
            {
                aggiornaCSV($connessione);
            }
            else
                die("Errore durante l'inserimento " . $connessione->error);
        }
        else
        {
            //se non vengono inseriti i campi specificati stampo un errore e il pulsante per tornare alla catalogazione
            echo '<h1>ERRORE. La modifica non è stata effettuata poichè non sono stati compilati i campi Genere e Volumi</h1>';
            echo '
                <form name="torna indietro" action="../Catalogazione/Catalogazione.php" method="post">
                    <input type="hidden" name="torna indietro" value="Torna alla Catalogazione"/>
                    <input type="submit" value="Torna alla Catalogazione"/>
                </form>
            ';
        }
            
        $connessione->close();
    }

    //funzione per l'aggiornamento dei dati nel file CSV
    function AggiornaCSV($connessione)
    {
        //se il file esiste lo elimino e recupero i dati dei libri dal DB
        if(file_exists("Back-up_Libri.csv"))
            unlink("Back-up_Libri.csv");
            
        $data = array("IDLibro", "Titolo", "Autori", "Casa_Editrice", "Luogo_stampa", "Anno_Pubblicazione", "ISBN", "Lingua", "Genere", "Data_Cat", "Volumi", "Numero");
        $file = fopen("Back-up_Libri.csv", "a");
        
        //inserisco la linea di formattazione nel CSV che generato automaticamente con fopen
        fputcsv($file, $data);
        
        $sql = "SELECT * FROM Libri ORDER BY Numero ASC";
        
        //eseguo la query di ricerca dei libri e li inserisco tutti nel CSV a fine operazione reindirizzo la pagina
        if($result = $connessione->query($sql))
        {
            foreach ($result as $row){
                $data = array($row['IDLibro'], $row['Titolo'], $row['Autori'], $row['Casa_Editrice'], $row['Luogo_stampa'], $row['Anno_Pubblicazione'], $row['ISBN'], $row['Lingua'], $row['Genere'], $row['DataCat'], $row['Volumi'], $row['Numero']);
                
                fputcsv($file, $data);
            }
            
            fclose($file);
            header("Location: ../Catalogazione/Catalogazione.php");
        }
        else
            die("Errore durante l'inserimento " . $connessione->error);
        }
    
?>