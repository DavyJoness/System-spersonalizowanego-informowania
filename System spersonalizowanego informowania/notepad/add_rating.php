<?php 
include 'core/init.php';
include 'includes/overall/header.php'; //naglowek z linkami do niego
include('db_connect.php');
protect_page(); //funkcja chroniąca strone przed osobami niezalogowanymi.
lecturer_protect();
function createForm($p_id_wyk='',$p_id_sprawdzian='', $p_id_grupa='', $error='') { //Tworzy nowy formularz?>

            <h2>Dodaj wyniki do sprawdzianu</h2>
            
            <?php if($error != '') {
                echo "<div style='color:red; padding: 5px'>" . $error . "</div>"; //Komunikat o błędzie
            } ?>
            <?php if(!count($p_id_grupa)==0) { ?>
            <form action="add_rating_commit.php" method="post">
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
                    <input type="submit" value="Przejdż dalej" /> <!-- Przesłanie zmian -->
                    
                </div>
            </form>
			<?php } else {echo "<p>Brak utworzonych sprawdzianów. </p>";} ?>

<?php }

    
    	$user_id = $_SESSION['user_id'];
		
    	$stmt = $mysqli->query("SELECT sprawdzian.id_sprawdzian, sprawdzian.nazwa, przedmiot.nazwa FROM sprawdzian, przedmiot WHERE sprawdzian.id_przedmiotu = przedmiot.id_przedmiotu AND id_wyk=".$user_id." ORDER BY przedmiot.nazwa, sprawdzian.nazwa");
		$sprawdziany = $stmt->fetch_all();
		$stmt->close();
		
		$stmt = $mysqli->query("call u_grupy_wyk(".$user_id.")");
		$grupy = $stmt->fetch_all();
		$stmt->close();
		$mysqli->next_result();
		
        createForm($user_id,$sprawdziany,$grupy,"");
    
    	$mysqli->close();

?>

 <?php include 'includes/overall/footer.php'; //stopka z linkami ?>