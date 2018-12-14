<?php 
include 'core/init.php';
include 'includes/overall/header.php'; //naglowek z linkami do niego
include('db_connect.php');
protect_page(); //funkcja chroniąca strone przed osobami niezalogowanymi.
admin_protect();
function createForm($error='') { //Tworzy nowy formularz?>

            <?php if($error != '') {
                echo "<div style='color:red; padding: 5px'>" . $error . "</div>"; //Komunikat o błędzie
            } ?>
            
            <h2>Wybierz plik z danymi użytkowników aby go importować</h2>
            
            <form enctype="multipart/form-data" action="#" method="POST">
            	<label for="plik">Plik: <input type="file" name="plik" required="required" /></label></br>
            	<input type="hidden" name="MAX_FILE_SIZE" value="1024" />
            	<input type="submit" name="submit" value="Prześlij plik" />
            </form>
<?php }


 if(isset($_POST['submit'])){
        $user_id = $_SESSION['user_id'];

		//Testowanie przeslanego pliku
		sprawdz_bledy();
		sprawdz_typ();
		$lokalizacja = zapisz_plik();
		
		if (file_exists($lokalizacja)) {
	    $xml = simplexml_load_file($lokalizacja);
		}

		foreach ($xml->uzytkownicy->user as $item) {
			if($stmt = $mysqli->prepare("INSERT INTO users VALUES (?,?,?,?,?,?,?)"))
			{
			    $stmt->bind_param("isssssi",$item->user_id,$item->username,$item->password,$item->first_name,$item->last_name,$item->email,$item->type);
                $stmt->execute();
			}
		}
		$stmt->close();
		$mysqli->close();
		
		echo "Poprawnie zaimportowano dane do bazy.";
        header("Refresh:3; backup.php");
    } else {	
		createForm("");
    }
    
?>

 <?php include 'includes/overall/footer.php'; //stopka z linkami ?>