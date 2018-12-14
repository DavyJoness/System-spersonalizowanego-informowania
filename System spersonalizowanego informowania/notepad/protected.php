<?php 

//W pliku menu trzeba podmienic nazwy jezeli chcemy zmienic nazwe pliku
include 'core/init.php';
include 'includes/overall/header.php'; //naglowek z linkami do niego?>
      <h1>Przepraszamy, musisz być zalogowany aby uzyskać dostęp.</h1>
      
	  
	  <?php
	  if(isset($_SESSION['user_id'])){
			echo 'Zalogowany';
	  } else {
		  echo 'Nie jesteś zalogowany.';
	  }
	  ?>
	  
	  <?php include 'includes/overall/footer.php'; //stopka z linkami ?>