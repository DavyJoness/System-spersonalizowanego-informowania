<?php

include('db_connect.php');

if(isset($_GET['user_id']) && is_numeric($_GET['user_id'])){ //Sprawdzamy czy id jest numeryczne
    
    
    $user_id = $_GET['user_id'];
    
    if($stmt = $mysqli->prepare("DELETE FROM users WHERE user_id = ? LIMIT 1")){ // ? oznacza ze to co wpiszemy bedzie tam wstawione
        $stmt->bind_param("i",$user_id); //i gdysz id jest zmienna typu integer
        $stmt->execute();
        $stmt->close();
    } else {
        echo "Blad zapytania 1";
    }
    
	if($stmt = $mysqli->prepare("DELETE FROM dost_do_przedmiot WHERE user_id = ?")){ // ? oznacza ze to co wpiszemy bedzie tam wstawione
    $stmt->bind_param("i",$user_id); //i gdysz id jest zmienna typu integer
    $stmt->execute();
    $stmt->close();
    } else {
        echo "Błąd zapytania 2";
    }
	
	if($stmt = $mysqli->prepare("DELETE FROM dost_do_grupa WHERE user_id = ?")){ // ? oznacza ze to co wpiszemy bedzie tam wstawione
    $stmt->bind_param("i",$user_id); //i gdysz id jest zmienna typu integer
    $stmt->execute();
    $stmt->close();
    } else {
        echo "Błąd zapytania 3";
    }
	
    $mysqli->close();
    
    header("Location: delete_user.php"); //Przekierowanie do strony index.php
    
    
} else {
    header("Location: index.php");
}