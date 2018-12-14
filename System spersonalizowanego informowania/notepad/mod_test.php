<?php 
include 'core/init.php';
include 'includes/overall/header.php'; //naglowek z linkami do niego
include('db_connect.php');
protect_page(); //funkcja chroniąca strone przed osobami niezalogowanymi.
lecturer_protect();
function createForm($p_id_wyk='',$p_id_sprawdzian='', $p_id_grupa='', $error='') { //Tworzy nowy formularz?>

    <h2>Przegląd wyników testu</h2>            
    <?php if($error != '') {
        echo "<div style='color:red; padding: 5px'>" . $error . "</div>"; //Komunikat o błędzie
    } ?>
    <?php if(!count($p_id_grupa)==0) { ?>
    <form action="#" method="post">
        <div>

			<h3> Wybierz sprawdzian i grupę studencką: </h3> 
			
			<p> <label>Sprawdzian: </label><br>
			<select name="id_sprawdzian">
				<?php
					foreach ($p_id_sprawdzian as $item) {
						echo "<option value='".$item[0]."'>".$item[2]." -> ".$item[1]."</option>";
					}
				 ?>
			</select></p>
			<p> <label>Grupa studencka: </label><br>
			<select name="id_grupa">
				<?php
					foreach ($p_id_grupa as $item) {
						echo "<option value='".$item[0]."'>".$item[1]."</option>";
					}
				 ?>
			</select></p>
            <input type="submit" name="submit" value="Sprawdź" /> <!-- Przesłanie zmian -->
            
        </div>
    </form>
	<?php } else {echo "<p>Brak utworzonych sprawdzianów. </p>";} ?>

<?php }
function informacje($id_sprawdzianu){
	include('db_connect.php');
	$zapytanie = "SELECT przedmiot.nazwa, sprawdzian.nazwa FROM sprawdzian, przedmiot WHERE sprawdzian.id_przedmiotu = przedmiot.id_przedmiotu and id_sprawdzian = ".$id_sprawdzianu;
	$stmt = $mysqli->query($zapytanie);
	$info = $stmt->fetch_row();
	$stmt->close();
	echo "<h1>Przedmiot: ".$info[0]."</h1>";
	echo "<h2>Nazwa sprawdzianu: ".$info[1]."</h2>";
}

if(isset($_POST['submit'])){
		$id_spr = htmlentities($_POST['id_sprawdzian'], ENT_QUOTES); 
        $id_grupa = htmlentities($_POST['id_grupa'], ENT_QUOTES); 
		
        informacje($id_spr);
        if($result = $mysqli->query("call u_pokaz_wyniki(".$id_spr.", ".$id_grupa.")")){ //definiujemy zapytanie
            
            if($result->num_rows > 0){ //Jeżeli liczba większa od 0 to generujemy tabele
                
                echo "<table border='1' cellpadding='10'>";
                
                echo "<tr><th>Nr indeksu</th><th>Ocena</th><th>Data dodania oceny</th></tr>";
                
                while($row = $result->fetch_object()){ //Generowanie 1 wiersza z 3 kolumnami
                    echo "<tr>";
                    echo "<td>" . $row->nr_index. "</td>";
                    echo "<td>" . ocena($row->ocena) . "</td>";
					echo "<td>" . $row->data. "</td>";
                    echo "<td><a href='edit_rating.php?nr_index=" . $row->nr_index . "&id_sprawdzian=".$id_spr."&data_dod=".$row->data."&cu_rating=".$row->ocena."'>Edytuj</a></td>";
                    echo "</tr>";
                }
                
                echo "</table>";
                
            } else {
                echo "Brak rekordów";
            }
			$result->close();
            $mysqli->next_result();
        } else {
            echo "Błąd: " . $mysqli->error;
        }
	
}else{
    	$user_id = $_SESSION['user_id'];
		
    	$stmt = $mysqli->query("SELECT sprawdzian.id_sprawdzian, sprawdzian.nazwa, przedmiot.nazwa FROM sprawdzian, przedmiot WHERE sprawdzian.id_przedmiotu = przedmiot.id_przedmiotu AND id_wyk=".$user_id." ORDER BY przedmiot.nazwa, sprawdzian.nazwa");
		$sprawdziany = $stmt->fetch_all();
		$stmt->close();
		
		$stmt = $mysqli->query("call u_grupy_wyk(".$user_id.")");
		$grupy = $stmt->fetch_all();
		$stmt->close();
		$mysqli->next_result();
		
        createForm($user_id,$sprawdziany,$grupy,"");
}  
    	$mysqli->close();

?>

 <?php include 'includes/overall/footer.php'; //stopka z linkami ?>