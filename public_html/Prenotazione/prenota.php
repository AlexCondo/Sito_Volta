<!DOCTYPE html>
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
</style>

<body>
<!-- form per la ricerca dei libri -->
<form method="post" class="form" action="<?php echo $_SERVER['PHP_SELF'];?>">

    <label for="Libro" style="font-family: arial">Ricerca:</label>
    <input type="text" id="libro" placeholder="Inserisci Libro" name="Libro">
    <br>
    <label for="Criterio" style="font-family: Arial">Criterio:</label>
    <br>
    <select name="Criterio" id="Criterio">
        <option value="1">Titolo</option>
        <option value="2">Autore</option>
        <option value="3">Casa Editrice</option>
        <option value="4">Anno Pubblicazione</option>
        <option value="5">Genere</option>
        <option value="6">Lingua</option>
    </select>
    <br>
    <input type="submit" value="cerca" style="font-family: arial">
</form>


<br><br>


<?php
$connessione = new mysqli("localhost", "id20210339_itivoltabiblioteca", "password", "id20210339_dbbiblioteca");
// Controlla se il form è stato inviato
// Salva i dati in una variabile
$libro = isset($_POST['Libro']) ? $_POST['Libro'] : '';
$criterio = isset($_POST['Criterio']) ? $_POST['Criterio'] : '1';

if ($libro != '') {
//controllo quale criterio di ricerca è stato inserito e in base a quello seleziono la query, se non è stato scelto un criterio non verrà mostrato alcun risultato
    switch ($criterio) {
        case 2:
            $sql = "SELECT Titolo, Autori, Casa_Editrice, Anno_Pubblicazione, Genere, Lingua, Volumi, IDLibro FROM Libri WHERE Autori LIKE '%$libro%' ORDER BY Titolo ASC";
            break;
        case 3:
            $sql = "SELECT Titolo, Autori, Casa_Editrice, Anno_Pubblicazione, Genere, Lingua, Volumi, IDLibro FROM Libri WHERE Casa_Editrice LIKE '%$libro%' ORDER BY Titolo ASC";
            break;
        case 4:
            $sql = "SELECT Titolo, Autori, Casa_Editrice, Anno_Pubblicazione, Genere, Lingua, Volumi, IDLibro FROM Libri WHERE Anno_Pubblicazione = $libro ORDER BY Titolo ASC";
            break;
        case 5:
            $sql = "SELECT Titolo, Autori, Casa_Editrice, Anno_Pubblicazione, Genere, Lingua, Volumi, IDLibro FROM Libri WHERE Genere LIKE '%$libro%' ORDER BY Titolo ASC";
            break;
        case 6:
            $sql = "SELECT Titolo, Autori, Casa_Editrice, Anno_Pubblicazione, Genere, Lingua, Volumi, IDLibro FROM Libri WHERE Lingua LIKE '%$libro%' ORDER BY Titolo ASC";
            break;
        default:
            $sql = "SELECT Titolo, Autori, Casa_Editrice, Anno_Pubblicazione, Genere, Lingua, Volumi, IDLibro FROM Libri WHERE Titolo LIKE '%$libro%' ORDER BY Titolo ASC";
            break;
    }

//stampo in una tabella tutti i libri risultanti
    if ($result = $connessione->query($sql)) {
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
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_array()) {
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
                                  <form action="Prenotazione/SelezioneDate.php" method="post">
                                        <input type="hidden" id="Ritiroid" name="Ritiroid" value="' . NULL . '">
                                        <input type="hidden" id="Consegnaid" name="Consegnaid" value="' . NULL . '">
                                        <input name="select_id" id="select_id" type="hidden" value="' . $row['IDLibro'] . '">
                                        <input type="submit" value="Seleziona">
                                  </form>
                            </td>
                          </tr>';
            }
            echo "</table>";

        } else
            echo '<b><h1>Nessun libro nel catalogo</h1></b>';
    } else
        echo "Errore DB $sql. " . $connessione->error;
}
$connessione->close();
?>
</body>
</html>