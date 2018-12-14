<?php 
include 'core/init.php';
include 'includes/overall/header.php'; //naglowek z linkami do niego
include('db_connect.php');
protect_page(); //funkcja chroniąca strone przed osobami niezalogowanymi.
admin_protect();
function createForm($p_grupy='',$error='') { //Tworzy nowy formularz?>
	
	<div style="width:620px">
            
            <?php if($error != '') {
                echo "<div style='color:red; padding: 5px'>" . $error . "</div>"; //Komunikat o błędzie
            } ?>
			<h2>Wybierz które grupy studenckie chcesz eksportować:</h2>
            <form action="group_download.php" method="post">
				<table border='1' cellpadding='10'>
					<tr><th></th><th>Nazwa grupy:</th></tr>
					<?php
					foreach ($p_grupy as $item) {
						echo "<tr>";
							echo "<td>";
								echo "<input type='checkbox' name='id_grupa[]' value='".$item[0]."' id='zaznacz' />";
							echo "</td>";
								
							echo "<td>";
								echo $item[1];
							echo "</td>";
								
						echo "</tr>";
					}   				
					?> 		
				</table>
				<label>Zaznacz wszystko: </label>
				<input type="checkbox" onclick="for(c in document.getElementsByName('id_grupa[]')) document.getElementsByName('id_grupa[]').item(c).checked = this.checked">
                <input type="submit" name="submit" value="Twórz kopie" style="display: block; clear: left;"/>
            </form>
		</div>

<?php }

$stmt = $mysqli->query("SELECT * FROM grupa");
$grupy = $stmt->fetch_all();
$stmt->close();

createForm($grupy,"");

$mysqli->close();

?>

 <?php include 'includes/overall/footer.php'; //stopka z linkami ?>