<?php 
include 'core/init.php';
include 'includes/overall/header.php'; //naglowek z linkami do niego
include('db_connect.php');
protect_page(); //funkcja chroniąca strone przed osobami niezalogowanymi.
lecturer_protect();
function createForm($p_sprawdziany='',$error='') { //Tworzy nowy formularz?>

	<div style="width:620px">
            
            <?php if($error != '') {
                echo "<div style='color:red; padding: 5px'>" . $error . "</div>"; //Komunikat o błędzie
            } ?>
            
            <?php if(!count($p_sprawdziany)==0) { ?>
			<h2>Wybierz które sprawdziany chcesz eksportować:</h2>
            <form action="download.php" method="post">
				<table border='1' cellpadding='10'>
					<tr><th></th><th>Nazwa sprawdzianu:</th><th>Data dodania:</th></tr>
					<?php
					foreach ($p_sprawdziany as $item) {
						echo "<tr>";
							echo "<td>";
								echo "<input type='checkbox' name='id_sprawdzian[]' value='".$item[0]."' />";
							echo "</td>";
								
							echo "<td>";
								echo $item[1]." -> ".$item[2];
							echo "</td>";
								
							echo "<td>";
								echo $item[3];
							echo "</td>";
						echo "</tr>";
					}   				
					?> 		
				</table>
				<label>Zaznacz wszystko: </label>
				<input type="checkbox" onclick="for(c in document.getElementsByName('id_sprawdzian[]')) document.getElementsByName('id_sprawdzian[]').item(c).checked = this.checked">
                
                <input type="submit" name="submit" value="Eksportuj wybrane sprawdziany" style="display: block; clear: left;"/>
            </form>
           	<?php } else {echo "<p>Brak utworzonych sprawdzianów. </p>";} ?>
		</div>

<?php }

$user_id = $_SESSION['user_id'];

$stmt = $mysqli->query("SELECT id_sprawdzian, przedmiot.nazwa, sprawdzian.nazwa, data FROM sprawdzian, przedmiot WHERE przedmiot.id_przedmiotu = sprawdzian.id_przedmiotu AND id_wyk=".$user_id);
$sprawdziany = $stmt->fetch_all();
$stmt->close();

createForm($sprawdziany,"");

$mysqli->close();

?>

 <?php include 'includes/overall/footer.php'; //stopka z linkami ?>