<?php 

//W pliku menu trzeba podmienic nazwy jezeli chcemy zmienic nazwe pliku
include 'core/init.php';
protect_page(); //funkcja chroniąca strone przed osobami niezalogowanymi.
lecturer_protect();
include 'includes/overall/header.php'; //naglowek z linkami do niego?>
<!--------------------------------------------------------------------------------------->
		<h1>Narzędzia użytkownika:</h1>
      
		<h3><a href="add_test.php">Dodaj sprawdzian</a></h3>
		<h3><a href="delete_test.php">Usuń sprawdzian</a></h3>
		<h3><a href="add_rating.php">Dodaj wyniki do sprawdzianu</a></h3>
		<h3><a href="mod_test.php">Przegląd wyników testu</a></h3>
		<h3 id="special"><a href="view_ratings.php">Generuj kod QR sprawdzianu</a></h3>
		<h3><a href="archive.php">Import/Export wyników do pliku</a></h3>

<!--------------------------------------------------------------------------------------->
	  <?php
	  if(isset($_SESSION['user_id'])){
		  if($user_data['type'] == 1){
		  echo 'Jesteś zalogowany'; }
		  else {
			  echo 'Jesteś zalogowany jako użytkownik.';
		  }
	  } else {
		  echo 'Aby mieć dostęp do różnych funkcji należy się zalogować.';?><br>
	<?php	  echo 'Nie jesteś zalogowany.';
	  }
	  ?>
	  
	  <?php include 'includes/overall/footer.php'; //stopka z linkami ?>