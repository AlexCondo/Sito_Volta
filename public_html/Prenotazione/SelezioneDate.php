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

    table{
        width: 80%;
        background-color: white;
        padding: 50px;
        border-radius: 10px;
        box-shadow: 2px 2px 10px gray;
        text-align: left;
        text-indent: 25px;
        border-collapse: collapse;
    }

    .form input[type="submit"],
    .form input[type="exit"],
    .form input[type="button"]{
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
<head>
    <meta charset="UTF-8">
    <title>Selezione Date</title>
</head>


<body>
<div class="header">
    <img src="../logo.png" style="display: block; margin: 0 auto; margin-left: -20%;">
</div>
<h1>PROPONI UNA DATA DI RITIRO E UNA DI CONSEGNA:</h1>
<?php
// Connessione al database
$connessione = new mysqli("localhost", "id20210339_itivoltabiblioteca", "password", "id20210339_dbbiblioteca");

// Recupero l'ID del libro dal form
$IDLibro = $_POST['select_id'];
// Controllo se è stata selezionata una richiesta
$IDRichiesta = isset($_POST['request_id']) ? $_POST['request_id'] : NULL;

// Recupero la data odierna
$currDate = date('Y-m-d');

// Controllo se sono state selezionate date di ritiro e consegna
if (isset($_POST['Ritiro']) && isset($_POST['Consegna'])) {
    $ritiro = $_POST['Ritiro'];
    $consegna = $_POST['Consegna'];
} else {
    $ritiro = NULL;
    $consegna = NULL;
}

// Query per recuperare i dati del libro selezionato
$sql = "SELECT IDLibro, Titolo, Autori FROM Libri WHERE IDLibro= '$IDLibro'";

if($result = $connessione->query($sql)) {
    if($result->num_rows > 0){

        // Recupero e stampo i dati del libro
        $row = $result -> fetch_array();
        echo '<table><th>';
        echo '<h3>Titolo:  '.$row['Titolo'].'</h3>';
        echo '<h3>Autore: '.$row['Autori'].'</h3>';
        echo '<h3>ID Libro: '.$row['IDLibro'].'</h3>';
        $sql = "SELECT COUNT(IDRichiesta) as Num_Prenotazioni_Attive FROM Richieste WHERE (Stato = '1' OR Stato = '0') and DATEDIFF('$currDate', DataConsegna) > 0";

        if($result = $connessione->query($sql)) {
            // Controllo se l'utente ha raggiunto il numero massimo di prenotazioni attive
            if($result->num_rows >=5 and $IDRichiesta == NULL){
                ?>
                <h2>Hai raggiunto il numero massimo di prenotazioni contemporanee (il massimo è di 5)</h2>
                <?php
            }else{
                // Controllo se non sono state selezionate date di ritiro e consegna
                if($consegna == NULL or $ritiro == NULL) {
                    // Form per inserire le date di ritiro e consegna
                    echo '<form method="post" class="form" action="SelezioneDate.php">
                                <label for="Ritiro" style="font-family: arial">Inserisci data ritiro:</label>
                                <input type="date" id="Ritiro" name="Ritiro" value="' .$currDate.'" min="'.$currDate.'" max="'.date('Y-m-d', strtotime('+2 months')).'">
                                <label for="Consegna" style="font-family: arial">Inserisci data Consegna:</label>
                                <input type="date" id="Consegna" name="Consegna" value="'.date('Y-m-d', strtotime('+1 days')).'" min="'.date('Y-m-d', strtotime('+1 days')).'" max="'.date('Y-m-d', strtotime('+3 months')).'">
                                <br>
                                <input type="hidden" id="select_id" name="select_id" value="'.$IDLibro.'">
                                <input type="hidden" id="request_id" name="request_id" value="'.$IDRichiesta.'">
                                <a href="../index.php"> <input type="button" value="Annulla"> </a>
                                <input type="submit" value="Valida date">
                            </form>';
                    //controllo se è stata inserita una data di ritiro successiva a quella di consegna
                } elseif ($ritiro > $consegna){
                    echo "<h3>inserire date di consegna e ritiro valide</h3>";
                    echo '<form method="post" class="form" action="SelezioneDate.php">
                                <label for="Ritiro" style="font-family: arial">Inserisci data ritiro:</label>
                                <input type="date" id="Ritiro" name="Ritiro" value="' .$currDate.'" min="'.$currDate.'" max="'.date('Y-m-d', strtotime('+2 months')).'">
                                <label for="Consegna" style="font-family: arial">Inserisci data Consegna:</label>
                                <input type="date" id="Consegna" name="Consegna" value="'.date('Y-m-d', strtotime('+1 days')).'" min="'.date('Y-m-d', strtotime('+1 days')).'" max="'.date('Y-m-d', strtotime('+3 months')).'">
                                <br>
                                <input type="hidden" id="select_id" name="select_id" value="'.$IDLibro.'">
                                <input type="hidden" id="request_id" name="request_id" value="'.$IDRichiesta.'">
                                <a href="../index.php"> <input type="button" value="Annulla"> </a>
                                <input type="submit" value="Valida date">
                             </form>';
                }else{
                    $sql = "SELECT IDRichiesta, DataConsegna, DataRitiro FROM Richieste WHERE ((Stato = '1') OR (Stato = '0')) AND (IDLibro = '$IDLibro') AND ((DataConsegna between '$ritiro' AND '$consegna') OR (DataRitiro between '$ritiro' AND '$consegna'))";
                    //controllo se il libro è stato già prenotato nel periodo selezionato
                    if ($result = $connessione->query($sql)){
                        if ($result->num_rows == 0){
                            echo '<h3>Ritiro: '.$ritiro.'</h3>';
                            echo '<h3>Consegna: '.$consegna.'</h3>';
                            echo '<br>';
                            echo '<form method="post" class="form" action="riepilogo.php">
                                <input type="hidden" id="Ritiro" name="Ritiro" value="'.$ritiro.'">
                                <input type="hidden" id="Consegna" name="Consegna" value="'.$consegna.'">
                                <input type="hidden" id="select_id" name="select_id" value="'.$IDLibro.'">
                                <input type="hidden" id="request_id" name="request_id" value="'.$IDRichiesta.'">
                                <a href="../index.php"> <input type="button" value="Annulla"> </a>
                                <input type="submit" value="Conferma Date">
                            </form>';
                        }else{
                            echo "<h3>Qualcuno ha già prenotato nelle date selezionate</h3>";
                            echo '<form method="post" class="form" action="SelezioneDate.php">
                                <label for="Ritiro" style="font-family: arial">Inserisci data ritiro:</label>
                                <input type="date" id="Ritiro" name="Ritiro" value="'.$currDate.'" min="'.$currDate.'" max="'.date('Y-m-d', strtotime('+2 months')).'">
                                <label for="Consegna" style="font-family: arial">Inserisci data Consegna:</label>
                                <input type="date" id="Consegna" name="Consegna" value="'.date('Y-m-d', strtotime('+1 days')).'" min="'.date('Y-m-d', strtotime('+1 days')).'" max="'.date('Y-m-d', strtotime('+3 months')).'">
                                <br>
                                <input type="hidden" id="select_id" name="select_id" value="'.$IDLibro.'">
                                <input type="hidden" id="request_id" name="request_id" value="'.$IDRichiesta.'">
                                <a href="../index.php"> <input type="button" value="Annulla"> </a>
                                <input type="submit" value="Valida date">
                            </form>';
                        }
                    }else{
                        echo "Errore DB $sql. " . $connessione->error;
                    }
                }
            }
        }
        echo "</th></table>";
    }else{
        ?>
        <h2>Il libro non esiste</h2>
        <?php
    }
}else{
    echo "Errore DB $sql. " . $connessione->error;
}
$connessione->close();
?>

</body>
</html>