<?php 
include 'core/init.php';
include 'includes/overall/header.php'; //naglowek z linkami do niego
include('db_connect.php');
protect_page(); //funkcja chroniąca strone przed osobami niezalogowanymi.
admin_protect();
function createForm($p_name='',$error='',$id='') { //Tworzy nowy formularz?>
	<p style="color: red"><?php echo $error ?></p>
            <form action="" method="post">
                <div>
                  
                    <p><label>Wpisz nazwę w odpowiedniej kolejności <br>
					Nazwa kierunku, rok rozpoczęcia, grupa  
					</label>
					<p>
					<input type="text" name="name" value="<?php echo $p_name; ?>" /></p>
                    <input type="submit" name="submit" value="Wyslij" /> <!-- Przesłanie zmian -->
                    
                </div>
            </form>
<?php }

  /*Tryb nowego rekordu*/
    
    if(isset($_POST['submit'])){
        
        $name = htmlentities($_POST['name'], ENT_QUOTES); 
		
		if(check_name_exist($name, 0)) //sprawdza czy wartosc $name nie jest juz w bazie
		{
		
	        if($name == ''){
	            $error = 'Wypełnij pole';
	            createForm($name,$error);
	        } else {
	        	//sprawdz czy nie ma juz takiej grupy, jak nie ma to nizej
	            if($stmt = $mysqli->prepare("insert into grupa values (NULL, ?)")){
	                $stmt->bind_param("s",$name);
	                $stmt->execute();
	                $stmt->close();
	            } else {
	                echo "Błąd zapytania";
	            }
	            
	            header("Location: group.php");
				//jak jest to info ze jest			
        	}
        }else{
			$error = 'Wartość '.$name." jest juz dodana do bazy.";
            createForm($name,$error);
		}
    } else {
        createForm();
    }
    


$mysqli->close();

?>
 <?php include 'includes/overall/footer.php'; //stopka z linkami ?>