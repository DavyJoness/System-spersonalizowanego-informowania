<?php
include('db_connect.php');
if(isset($_POST["id_user"])){
$id_user_przeslane = $_POST['id_user'];

$warunki = "";
foreach ($id_user_przeslane as $identyfikator) {
	$warunki .= "'".$identyfikator."',";
}
$warunki = substr($warunki, 0, strlen($warunki)-1);

$stmt = $mysqli->query("SELECT * FROM users WHERE user_id IN (".$warunki.") AND type=0 ORDER BY user_id");
$userBaza = $stmt->fetch_all();
$stmt->close();
}
else {
	header("Location: user_exp.php");
}
$docXML = new DOMDocument('1.0','utf-8');
$docXML->preserveWhiteSpace = false;
$docXML->formatOutput = true;

$dane = $docXML->createElement("dane");
$dane = $docXML->appendChild($dane);

$uzytkownicy = $docXML->createElement("uzytkownicy");
$uzytkownicy = $dane->appendChild($uzytkownicy);

foreach ($userBaza as $item) {
	$user = $docXML->createElement('user');
	$user = $uzytkownicy->appendChild($user);
	
	$user_id = $docXML->createElement("user_id");
	$user_id = $user->appendChild($user_id);
		$wartosc = $docXML->createTextNode($item[0]);
		$wartosc = $user_id->appendChild($wartosc);
		
	$username = $docXML->createElement("username");
	$username= $user->appendChild($username);
		$wartosc = $docXML->createTextNode($item[1]);
		$wartosc = $username->appendChild($wartosc);
		
	$password = $docXML->createElement("password");
	$password = $user->appendChild($password);
		$wartosc = $docXML->createTextNode($item[2]);
		$wartosc = $password->appendChild($wartosc);
		
	$first_name = $docXML->createElement("first_name");
	$first_name= $user->appendChild($first_name);
		$wartosc = $docXML->createTextNode($item[3]);
		$wartosc = $first_name->appendChild($wartosc);
		
	$last_name = $docXML->createElement("last_name");
	$last_name = $user->appendChild($last_name);
		$wartosc = $docXML->createTextNode($item[4]);
		$wartosc = $last_name->appendChild($wartosc);
		
	$email = $docXML->createElement("email");
	$email= $user->appendChild($email);
		$wartosc = $docXML->createTextNode($item[5]);
		$wartosc = $email->appendChild($wartosc);
		
	$type = $docXML->createElement("type");
	$type = $user->appendChild($type);
		$wartosc = $docXML->createTextNode($item[6]);
		$wartosc = $type->appendChild($wartosc);
		
}

$stmt = $mysqli->query("SELECT * FROM dost_do_grupa WHERE id_wyk IN (".$warunki.") ORDER BY id_grupa");
$dostBaza = $stmt->fetch_all();
$stmt->close();

$dostepy = $docXML->createElement("dostepyGrupa");
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

//Eksport dostepow

$stmt = $mysqli->query("SELECT * FROM dost_do_przedmiot WHERE id_wyk IN (".$warunki.") ORDER BY id_przedmiotu");
$dostBaza = $stmt->fetch_all();
$stmt->close();

$dostepy = $docXML->createElement("dostepyPrzedmiot");
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

$name = strftime('kopia_zapasowa_uzytkownicy_%m_%d_%Y.xml');
header('Content-Disposition: attachment;filename=' . $name);
header('Content-Type: text/xml');

echo $docXML->saveXML();
$mysqli->close();
?>