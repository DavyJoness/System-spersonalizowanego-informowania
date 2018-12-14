<?php 
include 'core/init.php';
include 'includes/overall/header.php'; //naglowek z linkami do niego
include('db_connect.php');
protect_page(); //funkcja chroniąca strone przed osobami niezalogowanymi.
lecturer_protect();
function createForm($error='') { //Tworzy nowy formularz?>

            <?php if($error != '') {
                echo "<div style='color:red; padding: 5px'>" . $error . "</div>"; //Komunikat o błędzie
            } ?>
            
            <h2>Wybierz plik z dysku aby go importować</h2>
            
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
		
		
		
		foreach($xml->sprawdzian as $item){
			if($stmt = $mysqli->prepare("INSERT INTO sprawdzian VALUES (?,?,?,?,?,?)"))
			{
			    $stmt->bind_param("iisssi",$item->id_spr,$item->id_przed,$item->nazwa,$item->data,$item->komentarz,$item->id_wyk);
                $stmt->execute();
			}
        }
	 	$stmt->close();
		
		foreach ($xml->oceny as $item) {
			if($stmt = $mysqli->prepare("INSERT INTO ocena VALUES (?,?,?,?)"))
			{
			    $stmt->bind_param("iiis",$item->nr_index,$item->id_sprawdzian,$item->ocena,$item->data);
                $stmt->execute();
			}
		}
		$stmt->close();
		echo "Poprawnie zaimportowano dane!";
        header("Refresh:3; database.php");
		
    } else {
    	$user_id = $_SESSION['user_id'];
		
		createForm("");
    }
    


$mysqli->close();

?>

 <?php include 'includes/overall/footer.php'; //stopka z linkami ?>