<?php 

//W pliku menu trzeba podmienic nazwy jezeli chcemy zmienic nazwe pliku
include 'core/init.php';
protect_page(); //funkcja chroniąca strone przed osobami niezalogowanymi.
admin_protect();
include 'includes/overall/header.php'; //naglowek z linkami do niego?>
      <h1>Narzędzia administratorskie:</h1>
      
		
		<h3><a href="group.php">Dodaj grupę</a></h3>
		<h3><a href="subject.php">Dodaj przedmiot</a></h3>
		<h3><a href="privileges.php">Uprawnienia użytkowników</a></h3>
		<h3><a href="student.php">Studenci</a></h3>
		<h3><a href="delete_user.php">Użytkownicy</a></h3>
		<h3><a href="backup.php">Wykonaj kopię zapasową</a></h3>
		<h3><a href="register.php">Zarejestruj użytkownika</a></h3>
		
	  	  <?php
	  if(isset($_SESSION['user_id'])){
		  if($user_data['type'] == 0){
		  echo 'Jesteś zalogowany'; }
		  else {
			  echo 'Jesteś zalogowany jako administrator.';
		  }
	  } else {
		  echo 'Aby mieć dostęp do różnych funkcji należy się zalogować.';?><br>
	<?php	  echo 'Nie jesteś zalogowany.';
	  }
	  ?>

	  
	  <?php include 'includes/overall/footer.php'; //stopka z linkami ?>