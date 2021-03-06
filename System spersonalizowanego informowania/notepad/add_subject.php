<?php 
include 'core/init.php';
include 'includes/overall/header.php'; //naglowek z linkami do niego
include('db_connect.php');

function createForm($imie_nazwisko='', $nazwa_przedmiot='' ,$error='') { //Tworzy nowy formularz?>

        

            <?php if($error != '') {
                echo "<div style='color:red; padding: 5px'>" . $error . "</div>"; //Komunikat o błędzie
            } ?>
			<h2>Uprawnienia do przedmiotu</h2>
            <form action="" method="post">
                <div>  
					<select name="wyk_naz"> 
						<?php 
							foreach($imie_nazwisko as $in)
							{
								echo "<option value='".$in[0]."'>".$in[1]." ".$in[2]."</option>";
							}
							
						?>
					</select>
					<select name="naz_przedmiot"> 
						<?php 
							foreach($nazwa_przedmiot as $grupa)
							{
								echo "<option value='".$grupa[0]."'>".$grupa[0]."</option>";
							}
							
						?>
					</select>
					<input type="hidden" value="1" name="submit">
		            <input type="submit" name="button" value="Przydziel" /> <!-- Przesłanie zmian -->
		            <input type="submit" name="button" value="Odbierz" />
                    
                </div>
            </form>
<?php }

if(isset($_POST['submit'])){
    switch ($_POST['button']) {
		case 'Przydziel':
      
            $id_wyk = htmlentities($_POST['wyk_naz'], ENT_QUOTES); //Walidacja
            $grupa = htmlentities($_POST['naz_przedmiot'], ENT_QUOTES);
			// stworz polecenie sql, ktore sprawdzi, czy dany wykladowca nie ma juz czasem dostepu do danej grupy
			$zapytanieSQL = "call a_sprawdz(".$id_wyk.", '".$grupa."', 1);";
			
			$check = $mysqli->query($zapytanieSQL);
			$ile = $check->num_rows;
			$check->close();
			$mysqli->next_result(); // polecenie wymagane po wykonaniu procedury w mysql
            if($ile > 0){
                $error = 'Dany wykładowca posada już dostęp do zadanego przedmiotu';
				echo $error;
            } else {
				
                if($stmt = $mysqli->prepare("call a_przydziel_przedmiot(?, ?);")){
                    $stmt->bind_param("is",$id_wyk,$grupa);
                    $stmt->execute();
                    $stmt->close();
					$mysqli->next_result();
					echo "Przydzielono uprawnienia.";
                } else {
                    echo "Błąd zapytania";
                } 
            }
            break;
			
		case 'Odbierz':
			
			$id_wyk = htmlentities($_POST['wyk_naz'], ENT_QUOTES); //Walidacja
            $grupa = htmlentities($_POST['naz_przedmiot'], ENT_QUOTES);
			// stworz polecenie sql, ktore sprawdzi, czy dany wykladowca nie ma juz czasem dostepu do danej grupy
			$zapytanieSQL = "call a_sprawdz(".$id_wyk.", '".$grupa."', 1);";
			
			$check = $mysqli->query($zapytanieSQL);
			$ile = $check->num_rows;
			$check->close();
			$mysqli->next_result(); // polecenie wymagane po wykonaniu procedury w mysql
            if($ile = 0){
                $error = 'Dany wykładowca nie posiada dostępu do zadanego przedmiotu';
				echo $error;
            } else {

                if($stmt = $mysqli->prepare("call a_odbierz_przedmiot(?, ?);")){
                    $stmt->bind_param("is",$id_wyk,$grupa);
                    $stmt->execute();
                    $stmt->close();
					$mysqli->next_result();
					echo "Odebrano uprawnienia.";
                } else {
                    echo "Błąd zapytania";
                } 
            }
			break;
			
			default:
			echo "Pojawił się nieznany problem.";
			break;
            }
		header("Refresh:2; add_subject.php");
    } else {
            if($stmt = $mysqli->query("SELECT user_id, first_name, last_name FROM users WHERE type=0 ORDER BY last_name")){
				$wykladowcy = $stmt->fetch_all();
				$stmt->close();
				$grupy = $mysqli->query("SELECT nazwa FROM przedmiot ORDER BY nazwa");
				$tablicaprzed = $grupy->fetch_all();
				$grupy->close();
                createForm($wykladowcy,$tablicaprzed,NULL);
                
            } else {
                echo "Błąd zapytania";
            }
    }
    


$mysqli->close();

?>
 <?php include 'includes/overall/footer.php'; //stopka z linkami ?>