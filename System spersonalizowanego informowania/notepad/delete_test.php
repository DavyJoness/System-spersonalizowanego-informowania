<?php 
include 'core/init.php';
include 'includes/overall/header.php'; //naglowek z linkami do niego
include('db_connect.php');
protect_page(); //funkcja chroniąca strone przed osobami niezalogowanymi.
lecturer_protect();
function createForm($p_sprawdziany='', $error='') { //Tworzy nowy formularz?>

	<h2>Usuń sprawdzian</h2>
    <?php if($error != '') {
        echo "<div style='color:red; padding: 5px'>" . $error . "</div>"; //Komunikat o błędzie
    } ?>
    <?php if(!count($p_sprawdziany)==0) { ?>
    <form action="#" method="post">
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

        <input type="submit" name="submit" value="Usuń wybrane sprawdziany" style="display: block; clear: left;"/>
    </form>
    <?php } else {echo "<p>Brak utworzonych sprawdzianów. </p>";} ?>
<?php }

function sprawdzPoprawnosc($id_wyk, $id_sprawdzian){
			include('db_connect.php');
			$polecenie = "SELECT * FROM sprawdzian WHERE id_wyk=".$id_wyk." AND id_sprawdzian=".$id_sprawdzian;
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
        $sprawdzian = $_POST['id_sprawdzian']; 
		$ilosc = count($sprawdzian);

        if($ilosc == 0){
            $error = 'Nie wybrałeś sprawdzianów';
	
			$stmt = $mysqli->query("SELECT id_sprawdzian, przedmiot.nazwa, sprawdzian.nazwa, data FROM sprawdzian, przedmiot WHERE przedmiot.id_przedmiotu = sprawdzian.id_przedmiotu AND id_wyk=".$id_wyk);
			$sprawdziany = $stmt->fetch_all();
			$stmt->close();
	        createForm($sprawdziany,$error);
	        exit;
        } else {
        	foreach ($sprawdzian as $item) {
				
        	if(!sprawdzPoprawnosc($id_wyk, $item)){//kod na sprawdzenie poprawnosci, czy uzytkownik nie edytowal kodu html
            if($stmt = $mysqli->prepare("call u_usun_sprawdzian(?)")){
                $stmt->bind_param("i",$item);
                $stmt->execute();
                $stmt->close();
				$mysqli->next_result();
				
            } else {
                echo "Błąd zapytania";
            }
			}else{
            	echo "Błąd uprawnień!";
            }
            
			} 
        }
		if($ilosc>1)
		echo "Sprawdziany zostały usunięte";
		else {
		echo "Sprawdzian został usunięty";
		}
        header("Refresh:3; database.php");
    } else {
	$user_id = $_SESSION['user_id'];
	
	$stmt = $mysqli->query("SELECT id_sprawdzian, przedmiot.nazwa, sprawdzian.nazwa, data FROM sprawdzian, przedmiot WHERE przedmiot.id_przedmiotu = sprawdzian.id_przedmiotu AND id_wyk=".$user_id." ORDER BY przedmiot.nazwa, sprawdzian.nazwa");
	$sprawdziany = $stmt->fetch_all();
	$stmt->close();
	
	createForm($sprawdziany,"");
	
	}
    


$mysqli->close();

?>

 <?php include 'includes/overall/footer.php'; //stopka z linkami ?>