<?php 
include 'core/init.php';
include 'includes/overall/header.php'; //naglowek z linkami do niego
include('db_connect.php');
protect_page(); //funkcja chroniąca strone przed osobami niezalogowanymi.
lecturer_protect();
function createForm($p_id_wyk='',$p_index='',$p_ocena='',$error='') { //Tworzy nowy formularz?>
  
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="https://jquery-ui.googlecode.com/svn-history/r3982/trunk/ui/i18n/jquery.ui.datepicker-pl.js"></script>
<script>
function zastosuj(){
	var data = document.getElementById('datepicker').value;
	for(c in document.getElementsByName('opt_date[]')) 
	document.getElementsByName('opt_date[]').item(c).value = data;
}

$(function() {
  $.datepicker.setDefaults( $.datepicker.regional[ "pl" ] );
  $( "#datepicker" ).datepicker({
  	dateFormat:"yy-mm-dd"
  });
  $( ".datepickers" ).datepicker({
  	dateFormat:"yy-mm-dd"
  });
});
</script>
  
<div style="width:620px">
    
    <?php if($error != '') {
        echo "<div style='color:red; padding: 5px'>" . $error . "</div>"; //Komunikat o błędzie
    } ?>
	<h2>Podaj wyniki do sprawdzianu:</h2>
    <form action="" method="post">
    	<label>Podaj datę wystawenia oceny: </label>
    	<input type="date" id="datepicker" required="required" onchange="zastosuj()"/>
		<table border='1' cellpadding='10'>
			<tr><th>Numer indeksu:</th><th>Ocena:</th><th>Data dodania:</th></tr>
			<?php
			$indexy = array();
			$i=0;
			foreach ($p_index as $item) {
				echo "<tr>";
					echo "<td>".$item[1].". ".$item[2]." -> ".$item[0]."</td>";
					echo "<td>";
						echo "<select name='rating_".$item[0]."' id='ocena_id'>";
								foreach ($p_ocena as $key => $value) {
									  echo "<option value='".$key."'>".$value."</option>";}
						echo "</select>";
					echo "</td>";
					
					echo "<td>";
						echo "<input type='date' name='opt_date[]' class='datepickers' />";
					echo "</td>";
				echo "</tr>"; 
				$indexy[$i]=$item[0];
				$i++;
			}   
			$_SESSION['numery_index']=$indexy;
			?> 		
		</table>
        <input type="submit" name="submit" value="Dodaj wyniki" style="display: block; clear: left;"/>
    </form>
</div>

<?php }

    if(isset($_POST['submit'])){
        $id_wyk = $_SESSION['user_id'];
		$id_sprawdzian = htmlentities($_SESSION['sprawdzian'], ENT_QUOTES); 
		$_SESSION['sprawdzian'] = null;
        $numer = $_SESSION['numery_index'];
		$_SESSION['numery_index'] = null;
		
		$data = $_POST['opt_date'];
		
		//zapisanie do tablicy asocjacyjnej wartosci $index => $ocena
		$counter = 0;
		$wynikiTablica = array();
		while(isset($_POST["rating_".$numer[$counter]]))
		{
			$i = $numer[$counter];
			$r = (int)$_POST["rating_".$numer[$counter]];
			$wynikiTablica[$i] = array("ocena" => $r, "data" => $data[$counter]);
			
			$counter++;
		}
		
		//Teraz juz można dodac do bazy
		$zapytanie="";
		foreach ($wynikiTablica as $index => $ocena) {
			$zapytanie .= "INSERT INTO ocena VALUES (".$index.", ".$id_sprawdzian.", ".$ocena['ocena'].", '".$ocena['data']."');";
		}

		if ($mysqli->multi_query($zapytanie) === TRUE) {
		    echo "Dodano nowe rekordy do bazy danych.";
		} else {
		    echo "Wyniki zostały dodane juz wcześniej.";
		}
		
        header("Refresh:3; database.php");
    } else {
    	$user_id = $_SESSION['user_id'];
		$_SESSION['sprawdzian'] = $_POST['id_sprawdzian'];
		$grupa = $_POST['id_grupa'];
		
    	$stmt = $mysqli->query("SELECT nr_index, substring( imie, 1, 1 ) , nazwisko FROM konto_student WHERE id_grupa = ".$grupa );
		$indexy = $stmt->fetch_all();
		$stmt->close();
		
		$oceny = array(0 => "Brak oceny", 20 => "Niedostateczny",30 => "Dostateczny",35 => "Dostateczny plus",40 => "Dobry",45 => "Dobry plus",50 => "Bardzo dobry", 10 => "Zaliczony", 5 => "Niezaliczony", 1 => "Nieobecny",);

        createForm($user_id,$indexy,$oceny,"");
    }
    


$mysqli->close();

?>

 <?php include 'includes/overall/footer.php'; //stopka z linkami ?>