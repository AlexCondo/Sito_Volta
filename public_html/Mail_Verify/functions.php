<?php
    
    session_start();
    
    //funzione per la registrazione degli utenti
    function signup($data)
    {
        //array controllo di flusso
        $errors = array();
        
        $email = $data['email'];
    
        //vincoli dei dati
        $pattern = '/^([a-z0-9.]+)(@itivoltativoliguidonia.net)$/';
        if(!preg_match($pattern, $email))
        {
            $errors[] = "Inserire una email con dominio scolastico";
        }
        
        if(strlen(trim($data['password'])) < 4)
        {
            $errors[] = "La password deve avere almeno 4 caratteri";
        }
        
        if(strlen(trim($data['password'])) > 25)
        {
            $errors[] = "La password può avere massimo 25 caratteri";
        }
        
        if($data['password'] != $data['password2'])
        {
            $errors[] = "Le password devono essere uguali";
        }
        
        $email = ['email' => $email];
        
        //se già esiste un account verificato aggiorno il controllo di flusso e non faccio inserimenti
        $check = database_run("SELECT email_verified FROM Utente WHERE email = :email", $email);
        
        if($check && $check[0]->email_verified){
            $errors[] = "Email già utilizzata"; 
        }
        
        //altrimenti elimino eventuali account non verificati collegati a quella email e ne creo uno nuovo
        if(count($errors) == 0)
        {
            $query = "DELETE FROM Utente WHERE email = :email";
            database_Signup($query, $email);
            $arr['email'] = $data['email'];
            $arr['password'] = hash('sha256', $data['password']);
            $arr['date'] = date("Y-m-d H:i:s");
            
            $query = "INSERT INTO Utente (email, password, date) VALUES (:email, :password, :date)";
            database_Signup($query, $arr);
        }
        //ritorno alla funzione chiamante eventuali errori
        return $errors;
    
        
    }
    
    //funzione che esegue le query che necessitano di una risposta
    function database_run($query, $vars = array())
    {
        //apro la connessione e imposto l'attributo per il controllo degli errori
        $string = "mysql:host=localhost;dbname=id20210339_dbbiblioteca";
        try{
            $con = new PDO($string,'id20210339_itivoltabiblioteca','password');
            
            $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            //eseguo la query, se ottengo un risultato lo ritorno alla funzione chiamante altrimenti non ritorno nulla, stampo eventuali errori
            $stm = $con->prepare($query);
            $check = $stm->execute($vars);
            
            if($check)
            {
                $data = $stm->fetchAll(PDO::FETCH_OBJ);
                if(count($data) > 0)
                {
                    return $data;
                }
            }
            return false;
            
        }catch(PDOException $e)
        {
            echo $query . "<br>" . $e->getMessage();
        }
    }
    
    //funzione che esegue le query che non devono ricevere una risposta
    function database_Signup($query, $vars = array())
    {
        //apro la connessione e imposto l'attributo per il controllo degli errori
        $string = "mysql:host=localhost;dbname=id20210339_dbbiblioteca";
        try{
            $con = new PDO($string,'id20210339_itivoltabiblioteca','password');
            
            $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            //eseguo la query e stampo eventuali errori
            $stm = $con->prepare($query);
            $check = $stm->execute($vars);
            
        }catch(PDOException $e)
        {
            echo $query . "<br>" . $e->getMessage();
        }
    }
    
    //funzione controllo Admin
    function is_admin($email)
    {
        $admin = 'email@admin.com';
        if ($email === $admin)
            return true;
        return false;
    }

    //funzione per il login degli utenti
    function login($data)
    {
        //genero l'array per il controllo degli errori
        $errors = array();
 
    	//controllo la validità dei dati
    	if(!filter_var($data['email'],FILTER_VALIDATE_EMAIL)){
    		$errors[] = "Please enter a valid email";
    	}
    
    	if(strlen(trim($data['password'])) < 4){
    		$errors[] = "Password must be atleast 4 chars long";
    	}
     
    	//se è tutto corretto controllo se l'utente esiste e se i dati sono corretti
    	if(count($errors) == 0){
    
    		$arr['email'] = $data['email'];
    		$password = hash('sha256', $data['password']);
    
    		$query = "SELECT * FROM Utente WHERE email = :email limit 1";
    
    		$row = database_run($query,$arr);
    
    		if(is_array($row)){
    			$row = $row[0];
    			if($password === $row->password){
    				if(!is_admin($arr['email']))
    				{
        				$_SESSION['USER'] = $row;
        				$_SESSION['LOGGED_IN'] = true;
    				}else
    				{
    				    $_SESSION['ADMIN'] = $row;
    				    $_SESSION['LOG_ADMIN'] = true;
    				}
    			}else{
    				$errors[] = "email o password sbagliate";
    			}
    		}else{
    			$errors[] = "email o password sbagliate";
    		}
    	}
    	//ritorno eventuali errori
    	return $errors;
    }

    //funzione per il controllo del login
    function check_login($redirect = true){
        //se l'utente è loggato ritorno true altrimenti lo reindirizzo alla pagina di login
    	if(isset($_SESSION['USER']) && isset($_SESSION['LOGGED_IN'])){
    
    		return true;
    	}
    
    	if($redirect){
    		header("Location: /login.php");
    		die;
    	}else{
    		return false;
    	}
    	
    }
    
    function check_login_admin($redirect = true){
        //se l'utente è loggato ritorno true altrimenti lo reindirizzo alla pagina di login
    	if(isset($_SESSION['ADMIN']) && isset($_SESSION['LOG_ADMIN'])){
    
    		return true;
    	}
    
    	if($redirect){
    		header("Location: /login.php");
    		die;
    	}else{
    		return false;
    	}
    	
    }
    
    //funzione per il controllo della verifica dell'account
    function check_verified(){
    
        //se l'utente ha i campi email e email_verified uguali significa che l'utente è verificato
    	$id = $_SESSION['USER']->id;
    	$query = "SELECT * FROM Utente WHERE id = '$id' limit 1";
    	$row = database_run($query);
    
    	if(is_array($row)){
    		$row = $row[0];
    
    		if($row->email == $row->email_verified){
    
    			return true;
    		}
    	}
    	return false;
    }

    //funzione per il controllo della verifica dell'account admin
    function check_verified_admin(){
    
        //se l'utente ha i campi email e email_verified uguali significa che l'utente è verificato
    	$id = $_SESSION['ADMIN']->id;
    	$query = "SELECT * FROM Utente WHERE id = '$id' limit 1";
    	$row = database_run($query);
    
    	if(is_array($row)){
    		$row = $row[0];
    
    		if($row->email == $row->email_verified){
    
    			return true;
    		}
    	}
    	return false;
    }

    //funzione per generazione codice di verifica
    function Code(){
        $caratteriPossibli = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        // inizializzo la stringa e il contatore
        $stringa = "";
        $i = 0;
        while ($i < 6) {
            // estrazione casuale di un un carattere dalla lista caratteriPossibili
            $carattere = substr($caratteriPossibli,rand(0,strlen($caratteriPossibli)-1),1);
            // prima di inserire il carattere controllo non sia già presente nella stringa random fin'ora creata
            if (!strstr($stringa, $carattere)) {
                $stringa .= $carattere;
                $i++;
            }
        }
        return $stringa;
    }
