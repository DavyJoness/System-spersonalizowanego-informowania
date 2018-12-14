<?php 
include 'core/init.php';
include 'includes/overall/header.php'; //naglowek z linkami do niego
include('db_connect.php');
include('core/cipher.php');
include('core/lib/qrlib.php');
protect_page(); //funkcja chroniąca strone przed osobami niezalogowanymi.
lecturer_protect();
function createForm($p_sciezka_do_png='', $p_przedmiot_nazwa='', $p_sprawdzian_nazwa='', $error='') { //Tworzy nowy formularz?>

            <?php if($error != '') {
                echo "<div style='color:red; padding: 5px'>" . $error . "</div>"; //Komunikat o błędzie
            } ?>
            <h2>Wygenerowany kod QR</h2>
            <div id="print">
            	<div id="info">
		            <h3>Przedmiot: <?php echo $p_przedmiot_nazwa; ?></h3>
		            <h4>Nazwa sprawdzianu: <?php echo $p_sprawdzian_nazwa; ?></h4>
	            </div>
	            <a href="<?php echo $p_sciezka_do_png; ?>">
	            	<img src="<?php echo $p_sciezka_do_png; ?>" alt="Problem z odczytem kodu QR" />
	            </a>
            </div>
            <a href="#" id="drukuj" onclick="window.print(); return false;">Drukuj kod QR</a>
<?php }

function generujCiag($daneSprawdzianu, $wyniki){
	$str = html_entity_decode($daneSprawdzianu[0])."|".html_entity_decode($daneSprawdzianu[1])."|".html_entity_decode($daneSprawdzianu[2])."|".html_entity_decode($daneSprawdzianu[3])."&";
	
	foreach ($wyniki as $key) {
		if($key[1] != 0 && $key[1] != 1)
		{$str .= $key[0]."|".$key[1]."#";}
	}
	$str = substr($str, 0, strlen($str)-1);
	return $str;
}


    	$user_id = $_SESSION['user_id'];
		$id_spr = htmlentities($_GET['id_sprawdzian'], ENT_QUOTES); 
        $id_grupa = htmlentities($_GET['id_grupa'], ENT_QUOTES); 
		
		if(commit_rights($user_id, $id_spr))
		{
			echo "Błąd formularza";
			exit;
		}

		$zapytanie = "SELECT sprawdzian.id_sprawdzian, przedmiot.nazwa, sprawdzian.nazwa, sprawdzian.komentarz FROM sprawdzian, przedmiot WHERE sprawdzian.id_przedmiotu = przedmiot.id_przedmiotu AND id_sprawdzian = ".$id_spr;

    	$stmt = $mysqli->query($zapytanie);
		$daneSprawdzianu = $stmt->fetch_row();
		$stmt->close();
		
		$stmt = $mysqli->query("call u_pokaz_wyniki(".$id_spr.", ".$id_grupa.")");
		$wyniki = $stmt->fetch_all();
		$stmt->close();
		$mysqli->next_result();
		
		$gotowyCiag = generujCiag($daneSprawdzianu, $wyniki);

		$obiekt = new szyfr();
		$text = $obiekt->encodingAES($gotowyCiag);

		$nazwaPliku = md5(date("Y-m-d H:i:s"));
		$nazwaPliku = "temp/".$nazwaPliku.".png";
		
		QRcode::png($text, $nazwaPliku, "L", 8, 1);
		
        createForm($nazwaPliku,$daneSprawdzianu[1],$daneSprawdzianu[2],null);

    	$mysqli->close();

?>

 <?php include 'includes/overall/footer.php'; //stopka z linkami ?>