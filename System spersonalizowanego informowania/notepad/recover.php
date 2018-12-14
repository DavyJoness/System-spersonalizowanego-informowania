<?php 

//W pliku menu trzeba podmienic nazwy jezeli chcemy zmienic nazwe pliku
include 'core/init.php';
logged_in_redirect();
include 'includes/overall/header.php'; //naglowek z linkami do niego?>
      <h1>Odzyskaj login lub hasło</h1>
   <?php
   if(isset($_GET['success']) === true && empty($_GET['success']) === true){
	   ?>
		<p> Właśnie została wysłana wiadomość na Twój adres email. </p>
	   <?php
   }
   else{
	   
   
	   $mode_allowed = array('username', 'password');
	   if(isset($_GET['mode']) === true && in_array($_GET['mode'], $mode_allowed) === true) {
		   if(isset($_POST['email']) === true && empty($_POST['email'] === false)) {
			   if(email_exists($_POST['email']) === true) {
				recover($_GET['mode'], $_POST['email']);
					header('Location: recover.php?success');
					exit();
			   }else{
				   echo '<p>Podany adres email nie znajduje się w bazie danych </p>';
			   }
	   }
		 ?>
		 <form action="" method="post">
			<ul>
				<li> Wpisz swój adres email: </li>
				<input type="text" name="email">
				</li>
				<li><input type="submit" value="Odzyskaj"> </li>
			</ul>
		</form>
		<?php
	   } else{
		   header('Location: index.php');
		   exit();
	   }
   }
   ?>
   
   <?php include 'includes/overall/footer.php'; ?>