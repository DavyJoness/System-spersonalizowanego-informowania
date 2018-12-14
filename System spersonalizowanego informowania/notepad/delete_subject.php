<?php

include('db_connect.php');

if(isset($_GET['id_przedmiotu']) && is_numeric($_GET['id_przedmiotu'])){ //Sprawdzamy czy id jest numeryczne
    
    
    $id_przedmiotu = $_GET['id_przedmiotu'];
    
    if($stmt = $mysqli->prepare("DELETE FROM przedmiot WHERE id_przedmiotu = ? LIMIT 1")){ // ? oznacza ze to co wpiszemy bedzie tam wstawione
        $stmt->bind_param("i",$id_przedmiotu); //i gdysz id jest zmienna typu integer
        $stmt->execute();
        $stmt->close();
    } else {
        echo "Błąd zapytania";
    }
    
	if($stmt = $mysqli->prepare("DELETE FROM dost_do_przedmiot WHERE id_przedmiotu = ?")){ // ? oznacza ze to co wpiszemy bedzie tam wstawione
        $stmt->bind_param("i",$id_przedmiotu); //i gdysz id jest zmienna typu integer
        $stmt->execute();
        $stmt->close();
    } else {
        echo "Błąd zapytania drugiego";
    }
    $mysqli->close();
    
    header("Location: subject.php"); //Przekierowanie do strony index.php
    
    
} else {
    header("Location: index.php");
}