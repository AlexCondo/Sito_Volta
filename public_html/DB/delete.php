<?php

    //apro la connessione
    $connessione = new mysqli("localhost", "id20210339_itivoltabiblioteca", "password", "id20210339_dbbiblioteca");
    if(!$connessione)
    {
        die("Errore di connessione: ". $connessione->connect_error);
    }
    else
    {
        //recupero l'id del libro da eliminare
        $ID = $_POST["id"];
        $sql = "DELETE FROM Libri WHERE IDLibro = '$ID'";
        
        //eseguo la query e aggiorno il CSV
        if($connessione->query($sql))
        {
            AggiornaCSV($connessione);
        }
        else
        {
            echo"Errore, " . $connessione->error;
        }
    }
    $connessione->close();
    
    function AggiornaCSV($connessione)
    {
        if(file_exists("Back-up_Libri.csv"))
            unlink("Back-up_Libri.csv");
            
        $data = array("IDLibro", "Titolo", "Autori", "Casa_Editrice", "Luogo_stampa", "Anno_Pubblicazione", "ISBN", "Lingua", "Genere", "Data_Cat", "Volumi", "Numero");
        $file = fopen("Back-up_Libri.csv", "a");
        
        fputcsv($file, $data);
        
        $sql = "SELECT * FROM Libri ORDER BY Numero ASC";
        
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
<html>
    <body>
        <form name="torna indietro" action="../Catalogazione/Catalogazione.php" method="post">
            <input type="hidden" name="torna indietro" value="Torna alla Catalogazione"/>
            <input type="submit" value="Torna alla Catalogazione"/>
        </form>
    </body>