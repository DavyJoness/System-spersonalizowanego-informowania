<?php
$connect_error="Przepraszamy, mamy problem z połączeniem z bazą danych";
mysql_connect('localhost','root','') or die($connect_error);
mysql_select_db('login') or die($connect_error);  //Nazwa bazy danych


?>