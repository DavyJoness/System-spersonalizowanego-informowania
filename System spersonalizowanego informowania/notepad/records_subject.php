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
          
            <p><label>Nazwa przedmiotu: </label> <br>
			<input type="text" name="name" value="<?php echo $p_name; ?>" /></p>
            <input type="submit" name="submit" value="Wyslij" /> <!-- Przesłanie zmian -->
            
        </div>
    </form>

<?php }

  /*Tryb nowego rekordu*/
    
    if(isset($_POST['submit'])){
        
        $name = htmlentities($_POST['name'], ENT_QUOTES); 
		
		if(check_name_exist($name, 1)) //sprawdza czy wartosc $name nie jest juz w bazie
		{
	        if($name == ''){
	            $error = 'Wypełnij pole';
	            createForm($name,$error);
	        } else {
	            if($stmt = $mysqli->prepare("insert into przedmiot values (NULL, ?)")){
	                $stmt->bind_param("s",$name);
	                $stmt->execute();
	                $stmt->close();
	            } else {
	                echo "Błąd zapytania";
	            }
	            
	            header("Location: subject.php");
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