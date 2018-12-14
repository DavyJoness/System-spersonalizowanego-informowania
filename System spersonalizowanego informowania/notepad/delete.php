<?php

include('db_connect.php');

if(isset($_GET['id']) && is_numeric($_GET['id'])){ //Sprawdzamy czy id jest numeryczne
    
    
    $id = $_GET['id'];
    
    if($stmt = $mysqli->prepare("DELETE FROM products WHERE id = ? LIMIT 1")){ // ? oznacza ze to co wpiszemy bedzie tam wstawione
        $stmt->bind_param("i",$id); //i gdysz id jest zmienna typu integer
        $stmt->execute();
        $stmt->close();
    } else {
        echo "Błąd zapytania";
    }
    
    $mysqli->close();
    
    header("Location: database.php"); //Przekierowanie do strony index.php
    
    
} else {
    header("Location: index.php");
}