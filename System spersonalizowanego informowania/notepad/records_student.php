<?php 
include 'core/init.php';
include 'includes/overall/header.php'; //naglowek z linkami do niego
include('db_connect.php');
protect_page(); //funkcja chroniąca strone przed osobami niezalogowanymi.
admin_protect();
function createForm($nr_index='',$p_imie='',$p_nazwisko='',$p_id_grupa='',$error='') { //Tworzy nowy formularz?>

    <?php if($error != '') {
        echo "<div style='color:red; padding: 5px'>" . $error . "</div>"; //Komunikat o błędzie
    } ?>
    
    <form action="" method="post">
        <div>
          
            <!-- Edycja imienia oraz nazwisko -->
			<h1> Dodaj rekord: </h1> 
			<p> <label>Nr indeksu: </label><br>
			<input type="text" name="nr_index" value="<?php echo $nr_index; ?>" /></p>
			<p> <label>Hasło: </label><br>
			<input type="password" name="pass" value="" /></p>
            <p><label>Imię: </label> <br>
			<input type="text" name="imie" value="<?php echo $p_imie; ?>" /></p>
            <p><label>Nazwisko: </label> <br>
			<input type="text" name="nazwisko" value="<?php echo $p_nazwisko; ?>" /></p>
			<p><label>Grupa: </label> <br>
			<select name="id_grupa">
				<?php
				foreach ($p_id_grupa as $grupa) {
				echo "<option value='".$grupa[0]."'>".$grupa[1]."</option>";
				}?>
			</select></p>
            <input type="submit" name="submit" value="Wyslij" /> <!-- Przesłanie zmian -->
            
        </div>
    </form>
<?php }


    if(isset($_POST['submit'])){
        $nr_index = htmlentities($_POST['nr_index'], ENT_QUOTES); 
		$pass = md5(htmlentities($_POST['pass'], ENT_QUOTES)); 
        $imie = htmlentities($_POST['imie'], ENT_QUOTES); 
        $nazwisko = htmlentities($_POST['nazwisko'], ENT_QUOTES);
        $id_grupa = htmlentities($_POST['id_grupa'], ENT_QUOTES);
		
		if(!is_numeric($nr_index)) //sprawdza czy wartosc indexu jest liczbowa
		{
			$error = 'Pole "Nr indeksu" przyjmije tylko wartosci liczbowe.';
			$stmt = $mysqli->query("SELECT * FROM grupa");
			$grupy = $stmt->fetch_all();
			$stmt->close();
            createForm("",$imie,$nazwisko,$grupy,$error);
			$mysqli->close();
			exit;
		}
		
        if($nr_index == '' || $imie == '' || $nazwisko == '' || $id_grupa == ''){
            $error = 'Wypełnij wszystkie pola';
			$stmt = $mysqli->query("SELECT * FROM grupa");
			$grupy = $stmt->fetch_all();
			$stmt->close();
            createForm($nr_index,$imie,$nazwisko,$grupy,$error);
        } else {
            if($stmt = $mysqli->prepare("INSERT konto_student (nr_index,pass,imie,nazwisko,id_grupa) VALUES (?,?,?,?,?)")){
                $stmt->bind_param("isssi",$nr_index,$pass,$imie,$nazwisko,$id_grupa);
                $stmt->execute();
                $stmt->close();
            } else {
                echo "Błąd zapytania";
            }
            
            header("Location: student.php");
        }
        
    } else {
    	$stmt = $mysqli->query("SELECT * FROM grupa");
		$grupy = $stmt->fetch_all();
		$stmt->close();
        createForm("","","",$grupy,"");
    }
    


$mysqli->close();

?>

 <?php include 'includes/overall/footer.php'; //stopka z linkami ?>