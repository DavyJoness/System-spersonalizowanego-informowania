

<?php

include('db_connect.php');

if(isset($_GET['nr_index']) && is_numeric($_GET['nr_index'])){ //Sprawdzamy czy id jest numeryczne
    
    
    $nr_index = $_GET['nr_index'];
    
    if($stmt = $mysqli->prepare("DELETE FROM konto_student WHERE nr_index = ? LIMIT 1")){ // ? oznacza ze to co wpiszemy bedzie tam wstawione
        $stmt->bind_param("i",$nr_index); //i gdysz id jest zmienna typu integer
        $stmt->execute();
        $stmt->close();
    } else {
        echo "Błąd zapytania";
    }
    
    $mysqli->close();
    
    header("Location: student.php"); //Przekierowanie do strony index.php
    
    
} else {
    header("Location: index.php");
}