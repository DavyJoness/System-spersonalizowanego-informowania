<?php

include('db_connect.php');

if(isset($_GET['id_grupa']) && is_numeric($_GET['id_grupa'])){ //Sprawdzamy czy id jest numeryczne
    
    
    $id_grupa = $_GET['id_grupa'];
    
    if($stmt = $mysqli->prepare("DELETE FROM grupa WHERE id_grupa = ? LIMIT 1")){ // ? oznacza ze to co wpiszemy bedzie tam wstawione
        $stmt->bind_param("i",$id_grupa); //i gdysz id jest zmienna typu integer
        $stmt->execute();
        $stmt->close();
    } else {
        echo "Błąd zapytania";
		exit;
    }
    
	if($stmt = $mysqli->prepare("DELETE FROM konto_student WHERE id_grupa = ?")){ // ? oznacza ze to co wpiszemy bedzie tam wstawione
        $stmt->bind_param("i",$id_grupa); //i gdysz id jest zmienna typu integer
        $stmt->execute();
        $stmt->close();
    } else {
        echo "Błąd zapytania 2";
		exit;
    }
	
	if($stmt = $mysqli->prepare("DELETE FROM dost_do_grupa WHERE id_grupa = ?")){ // ? oznacza ze to co wpiszemy bedzie tam wstawione
        $stmt->bind_param("i",$id_grupa); //i gdysz id jest zmienna typu integer
        $stmt->execute();
        $stmt->close();
    } else {
        echo "Błąd zapytania 3";
		exit;
    }
    $mysqli->close();
    
    header("Location: group.php"); //Przekierowanie do strony index.php
    
    
} else {
    header("Location: index.php");
}