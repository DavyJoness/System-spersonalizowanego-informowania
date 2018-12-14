<?php 
include 'core/init.php';
include 'includes/overall/header.php'; //naglowek z linkami do niego
include('db_connect.php');
protect_page(); //funkcja chroniąca strone przed osobami niezalogowanymi.
lecturer_protect();
function createForm($p_id_wyk='',$p_przedmiot='',$p_nazwa='',$p_komentarz='',$error='') { //Tworzy nowy formularz?>
      <h2> Dodaj sprawdzian: </h2>      
<?php if($error != '') {
    echo "<div style='color:red; padding: 5px'>" . $error . "</div>"; //Komunikat o błędzie
} ?>

<form action="" method="post">
    <div>
      
        <!-- Edycja imienia oraz nazwisko -->
		
		<p> <label>Nazwa sprawdzianu: </label><br>
		<input type="text" name="nazwa" value="<?php echo $p_nazwa; ?>" /></p>
		<p> <label>Przedmiot: </label><br>
		<?php if(!count($p_przedmiot)==0){ ?>
		<select name="subject">
			<?php
				foreach ($p_przedmiot as $item) {
					echo "<option value='".$item[0]."'>".$item[1]."</option>";
				}
			 ?>
		</select></p>
		<?php }else{echo "<p>Brak przypisanych grup do tego użytkownika. </p>";} ?>
        <p><label>Komentarz: </label> <br>
		<textarea name="komentarz" rows="5" cols="25" maxlength="50"><?php echo $p_komentarz; ?></textarea></p>
        <input type="submit" name="submit" value="Wyslij" /> <!-- Przesłanie zmian -->
        
    </div>
</form>


<?php }

function sprawdzPoprawnosc($id_wyk, $id_przedmiot){
			include('db_connect.php');
			$polecenie = "SELECT * FROM dost_do_przedmiot WHERE id_wyk=".$id_wyk." AND id_przedmiotu=".$id_przedmiot;
			$stmt = $mysqli->query($polecenie);
			$ilosc = $stmt->num_rows;
			$stmt->close();
			if($ilosc>0){
			return false;}
			else {
				return true;
			}
}

    if(isset($_POST['submit'])){
        $id_wyk = $_SESSION['user_id'];
		$nazwa_spr = htmlentities($_POST['nazwa'], ENT_QUOTES); 
        $przedmiot = htmlentities($_POST['subject'], ENT_QUOTES); 
        $komentarz = htmlentities($_POST['komentarz'], ENT_QUOTES);
        $data = date("Y-m-d H:i:s");
		
        if($id_wyk == '' || $nazwa_spr == '' || $przedmiot == ''){
            $error = 'Wypełnij wszystkie pola';
			$user_id = $_SESSION['user_id'];
	    	$stmt = $mysqli->query("call u_przedmioty_wyk(".$user_id.")");
			$przedmioty = $stmt->fetch_all();
			$stmt->close();
			$mysqli->next_result();
	        createForm($user_id,$przedmioty,"","",$error);
        } else {
        	
        	if(!sprawdzPoprawnosc($id_wyk, $przedmiot)){//kod na sprawdzenie poprawnosci, czy uzytkownik nie edytowal kodu html
            if($stmt = $mysqli->prepare("INSERT sprawdzian (id_sprawdzian,id_przedmiotu,nazwa,data,komentarz,id_wyk) VALUES (NULL,?,?,?,?,?)")){
                $stmt->bind_param("isssi",$przedmiot,$nazwa_spr,$data,$komentarz,$id_wyk);
                $stmt->execute();
                $stmt->close();
				echo "Dodano do bazy danych";
            } else {
                echo "Błąd zapytania";
            }
			}else{
            	echo "Błąd uprawnień!";
            }
            
            header("Refresh:3; database.php");
        }
        
    } else {
    	$user_id = $_SESSION['user_id'];
    	$stmt = $mysqli->query("call u_przedmioty_wyk(".$user_id.")");
		$przedmioty = $stmt->fetch_all();
		$stmt->close();
		$mysqli->next_result();
        createForm($user_id,$przedmioty,"","","");
    }
    


$mysqli->close();

?>

 <?php include 'includes/overall/footer.php'; //stopka z linkami ?>