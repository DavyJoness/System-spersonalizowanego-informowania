<?php

$server = 'localhost'; //Server
$user = 'root'; // nazwa uzytkownika
$pass = '';  // bez hasla
$db = 'login';
//$db = 'products'; //nazwa bazy danych

$mysqli = new mysqli($server,$user,$pass,$db); //Konstruktor przejmuje ustawnienia z 4 zmiennych