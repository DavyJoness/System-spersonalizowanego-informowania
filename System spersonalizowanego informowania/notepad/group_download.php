<?php
include('db_connect.php');
if(isset($_POST["id_grupa"])){
$id_grup_przeslane = $_POST['id_grupa'];

$warunki = "";
foreach ($id_grup_przeslane as $identyfikator) {
	$warunki .= "'".$identyfikator."',";
}
$warunki = substr($warunki, 0, strlen($warunki)-1);

$stmt = $mysqli->query("SELECT * FROM grupa WHERE id_grupa IN (".$warunki.") ORDER BY id_grupa");
$grupyBaza = $stmt->fetch_all();
$stmt->close();
}
else {
	header("Location: group_exp.php");
}
$docXML = new DOMDocument('1.0','utf-8');
$docXML->preserveWhiteSpace = false;
$docXML->formatOutput = true;

$dane = $docXML->createElement("dane");
$dane = $docXML->appendChild($dane);

$grupy = $docXML->createElement("grupy");
$grupy = $dane->appendChild($grupy);

foreach ($grupyBaza as $item) {
	$grupa = $docXML->createElement('grupa');
	$grupa = $grupy->appendChild($grupa);
	
	$id_grupa = $docXML->createElement("id_grupa");
	$id_grupa = $grupa->appendChild($id_grupa);
		$wartosc = $docXML->createTextNode($item[0]);
		$wartosc = $id_grupa->appendChild($wartosc);
		
	$nazwa = $docXML->createElement("nazwa");
	$nazwa= $grupa->appendChild($nazwa);
		$wartosc = $docXML->createTextNode($item[1]);
		$wartosc = $nazwa->appendChild($wartosc);
}

//Eksport studentow

$stmt = $mysqli->query("SELECT * FROM konto_student WHERE id_grupa IN (".$warunki.") ORDER BY id_grupa, nr_index");
$studenciBaza = $stmt->fetch_all();
$stmt->close();

$studenci = $docXML->createElement("studenci");
$studenci = $dane->appendChild($studenci);

foreach ($studenciBaza as $item) {
	$student = $docXML->createElement('student');
	$student = $studenci->appendChild($student);
	
	$nr_index = $docXML->createElement("nr_index");
	$nr_index = $student->appendChild($nr_index);
		$wartosc = $docXML->createTextNode($item[0]);
		$wartosc = $nr_index->appendChild($wartosc);
		
	$pass = $docXML->createElement("pass");
	$pass= $student->appendChild($pass);
		$wartosc = $docXML->createTextNode($item[1]);
		$wartosc = $pass->appendChild($wartosc);
		
	$imie = $docXML->createElement("imie");
	$imie = $student->appendChild($imie);
		$wartosc = $docXML->createTextNode($item[2]);
		$wartosc = $imie->appendChild($wartosc);	
		
	$nazwisko = $docXML->createElement("nazwisko");
	$nazwisko= $student->appendChild($nazwisko);
		$wartosc = $docXML->createTextNode($item[3]);
		$wartosc = $nazwisko->appendChild($wartosc);
		
	$id_grupa = $docXML->createElement("id_grupa");
	$id_grupa = $student->appendChild($id_grupa);
		$wartosc = $docXML->createTextNode($item[4]);
		$wartosc = $id_grupa->appendChild($wartosc);
}

//Eksport dostepow

$stmt = $mysqli->query("SELECT * FROM dost_do_grupa WHERE id_grupa IN (".$warunki.") ORDER BY id_grupa");
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
		
	$id_grupa = $docXML->createElement("id_grupa");
	$id_grupa= $dostep->appendChild($id_grupa);
		$wartosc = $docXML->createTextNode($item[1]);
		$wartosc = $id_grupa->appendChild($wartosc);
}

$name = strftime('kopia_zapasowa_grupy_%m_%d_%Y.xml');
header('Content-Disposition: attachment;filename=' . $name);
header('Content-Type: text/xml');

echo $docXML->saveXML();
$mysqli->close();
?>