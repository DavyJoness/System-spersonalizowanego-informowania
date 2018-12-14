<?php
include('db_connect.php');
if(isset($_POST["id_przedmiot"])){
$id_przedmiot_przeslane = $_POST['id_przedmiot'];

$warunki = "";
foreach ($id_przedmiot_przeslane as $identyfikator) {
	$warunki .= "'".$identyfikator."',";
}
$warunki = substr($warunki, 0, strlen($warunki)-1);

$stmt = $mysqli->query("SELECT * FROM przedmiot WHERE id_przedmiotu IN (".$warunki.") ORDER BY id_przedmiotu");
$przedmiotyBaza = $stmt->fetch_all();
$stmt->close();
}
else {
	header("Location: subject_exp.php");
}
$docXML = new DOMDocument('1.0','utf-8');
$docXML->preserveWhiteSpace = false;
$docXML->formatOutput = true;

$dane = $docXML->createElement("dane");
$dane = $docXML->appendChild($dane);

$przedmioty = $docXML->createElement("przedmioty");
$przedmioty = $dane->appendChild($przedmioty);

foreach ($przedmiotyBaza as $item) {
	$przedmiot = $docXML->createElement('przedmiot');
	$przedmiot = $przedmioty->appendChild($przedmiot);
	
	$id_przedmiotu = $docXML->createElement("id_przedmiotu");
	$id_przedmiotu = $przedmiot->appendChild($id_przedmiotu);
		$wartosc = $docXML->createTextNode($item[0]);
		$wartosc = $id_przedmiotu->appendChild($wartosc);
		
	$nazwa = $docXML->createElement("nazwa");
	$nazwa= $przedmiot->appendChild($nazwa);
		$wartosc = $docXML->createTextNode($item[1]);
		$wartosc = $nazwa->appendChild($wartosc);
}

//Eksport dostepow

$stmt = $mysqli->query("SELECT * FROM dost_do_przedmiot WHERE id_przedmiotu IN (".$warunki.") ORDER BY id_przedmiotu");
$dostBaza = $stmt->fetch_all();
$stmt->close();

$dostepy = $docXML->createElement("dostepy");
$dostepy = $dane->appendChild($dostepy);

foreach ($dostBaza as $item) {
	$dostep = $docXML->createElement('dostep');
	$dostep = $dostepy->appendChild($dostep);
	
	$id_wyk = $docXML->createElement("id_wyk");
	$id_wyk = $dostep->appendChild($id_wyk);
		$wartosc = $docXML->createTextNode($item[0]);
		$wartosc = $id_wyk->appendChild($wartosc);
		
	$id_grupa = $docXML->createElement("id_przedmiotu");
	$id_grupa= $dostep->appendChild($id_grupa);
		$wartosc = $docXML->createTextNode($item[1]);
		$wartosc = $id_grupa->appendChild($wartosc);
}

$name = strftime('kopia_zapasowa_przedmioty_%m_%d_%Y.xml');
header('Content-Disposition: attachment;filename=' . $name);
header('Content-Type: text/xml');

echo $docXML->saveXML();
$mysqli->close();
?>