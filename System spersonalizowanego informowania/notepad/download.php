<?php
include('db_connect.php');
if(isset($_POST["id_sprawdzian"])){
$id_sprawdzianow_przeslane = $_POST['id_sprawdzian'];

$warunki = "";
foreach ($id_sprawdzianow_przeslane as $identyfikator) {
	$warunki .= "'".$identyfikator."',";
}
$warunki = substr($warunki, 0, strlen($warunki)-1);

$stmt = $mysqli->query("SELECT * FROM sprawdzian WHERE id_sprawdzian IN (".$warunki.") ORDER BY id_sprawdzian");
$sprawdzianyBaza = $stmt->fetch_all();
$stmt->close();
}
else {
	header("Location: export.php");
}
$docXML = new DOMDocument('1.0','utf-8');
$docXML->preserveWhiteSpace = false;
$docXML->formatOutput = true;

$sprawdziany = $docXML->createElement("wyniki");
$sprawdziany = $docXML->appendChild($sprawdziany);

foreach ($sprawdzianyBaza as $item) {
	$sprawdzian = $docXML->createElement('sprawdzian');
	$sprawdzian = $sprawdziany->appendChild($sprawdzian);
	
	$id_spr = $docXML->createElement("id_spr");
	$id_spr = $sprawdzian->appendChild($id_spr);
		$wartosc = $docXML->createTextNode($item[0]);
		$wartosc = $id_spr->appendChild($wartosc);
		
	$id_przed = $docXML->createElement("id_przed");
	$id_przed= $sprawdzian->appendChild($id_przed);
		$wartosc = $docXML->createTextNode($item[1]);
		$wartosc = $id_przed->appendChild($wartosc);
		
	$nazwa = $docXML->createElement("nazwa");
	$nazwa = $sprawdzian->appendChild($nazwa);
		$wartosc = $docXML->createTextNode($item[2]);
		$wartosc = $nazwa->appendChild($wartosc);
		
	$data = $docXML->createElement("data");
	$data = $sprawdzian->appendChild($data);
		$wartosc = $docXML->createTextNode($item[3]);
		$wartosc = $data->appendChild($wartosc);
		
	$komentarz = $docXML->createElement("komentarz");
	$komentarz = $sprawdzian->appendChild($komentarz);
		$wartosc = $docXML->createTextNode($item[4]);
		$wartosc = $komentarz->appendChild($wartosc);	
		
	$id_wyk = $docXML->createElement("id_wyk");
	$id_wyk = $sprawdzian->appendChild($id_wyk);
		$wartosc = $docXML->createTextNode($item[5]);
		$wartosc = $id_wyk->appendChild($wartosc);	
}

//Eksport ocen

$stmt = $mysqli->query("SELECT * FROM ocena WHERE id_sprawdzian IN (".$warunki.") ORDER BY id_sprawdzian, nr_index");
$ocenyBaza = $stmt->fetch_all();
$stmt->close();

foreach ($ocenyBaza as $item) {
	$oceny = $docXML->createElement('oceny');
	$oceny = $sprawdziany->appendChild($oceny);
	
	$nr_index = $docXML->createElement("nr_index");
	$nr_index = $oceny->appendChild($nr_index);
		$wartosc = $docXML->createTextNode($item[0]);
		$wartosc = $nr_index->appendChild($wartosc);
		
	$id_sprawdzian = $docXML->createElement("id_sprawdzian");
	$id_sprawdzian= $oceny->appendChild($id_sprawdzian);
		$wartosc = $docXML->createTextNode($item[1]);
		$wartosc = $id_sprawdzian->appendChild($wartosc);
		
	$ocena = $docXML->createElement("ocena");
	$ocena = $oceny->appendChild($ocena);
		$wartosc = $docXML->createTextNode($item[2]);
		$wartosc = $ocena->appendChild($wartosc);	
		
	$data = $docXML->createElement("data");
	$data = $oceny->appendChild($data);
		$wartosc = $docXML->createTextNode($item[3]);
		$wartosc = $data->appendChild($wartosc);
}

$docXML->SaveXML();
$docXML->save('temp/kopie/plik.xml');

$name = strftime('kopia_%m_%d_%Y.xml');
header('Content-Disposition: attachment;filename=' . $name);
header('Content-Type: text/xml');

echo $docXML->saveXML();

?>