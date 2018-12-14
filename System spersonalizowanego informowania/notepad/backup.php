<?php 

//W pliku menu trzeba podmienic nazwy jezeli chcemy zmienic nazwe pliku
include 'core/init.php';
protect_page(); //funkcja chroniąca strone przed osobami niezalogowanymi.
admin_protect();
include 'includes/overall/header.php'; //naglowek z linkami do niego?>
<!--------------------------------------------------------------------------------------->
	 <h1>Jaką czynność chcesz wykonac?</h1>
    
    	<h2 style="color: blue">Stworzenie kopii zapasowej</h2>
		<h3><a href="group_exp.php">Wykonaj kopię zapasowa grup studenckich</a></h3>
		<h3><a href="subject_exp.php">Wykonaj kopię zapasowa przedmiotów</a></h3>
		<h3><a href="user_exp.php">Wykonaj kopię zapasowa kont użytkowników</a></h3>
		
		<h2 style="color: blue">Przywrócenie kopii zapasowej</h2>
		<h3><a href="group_imp.php">Importuj grupy studenckie</a></h3>
		<h3><a href="subject_imp.php">Importuj przedmioty</a></h3>
		<h3><a href="user_imp.php">Importuj konta użytkowników</a></h3>
	
 
<!--------------------------------------------------------------------------------------->
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