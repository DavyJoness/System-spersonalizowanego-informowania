<?php 
include 'core/init.php';
//Trzeba to wylaczyc dla admina pozniej
//logged_in_redirect(); //Ta funkcja przekierowuje na strone glowna jezeli ktos jest zalogowany i sprobuje sie zarejestrowac.
protect_page(); //funkcja chroniąca strone przed osobami niezalogowanymi.
admin_protect();
include 'includes/overall/header.php'; //naglowek z linkami do niego

if(empty($_POST) === false){
	$required_fields = array('username','password','password_again','first_name','last_name','email');
	foreach($_POST as $key=>$value) {
		if(empty($value) && in_array($key, $required_fields) === true) {
			$errors[] = 'Należy wypełnić wszystkie pola!';
			break 1;
		}
	}
	
	if (empty($errors) === true) {
		if(user_exists($_POST['username']) === true) {
			$errors[] = 'Przepraszamy, użytkownik \''. $_POST['username'] . '\' już jest zarejestrowany.';
			
		}
		if(preg_match("/\\s/", $_POST['username']) == true) {
			$error[] = 'Nazwa użytkownika nie może zawierać spacji.';
		}
		if(strlen($_POST['password']) < 6) {
			$error[] = 'Twoje hasło musi składać się przynajmniej z 6-iu znaków.';
		}
		if($_POST['password'] !== $_POST['password_again']) {
			$errors[] = 'Podane hasła nie są zgodne.';
		}
		 if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false){
			 $errors[] = 'Wpisany adres email jest niepoprawny.';
		 }
		 if(email_exists($_POST['email']) === true){
			 $errors[] = 'Przepraszamy, podany adres email jest już zajęty.';
		 }
		
	}
}


?>
      <h1>Rejestracja</h1>
	  
	  <?php
	  if (isset($_GET['success']) && empty($_GET['success'])){
		  echo 'Zarejestowanie powiodło się.';
	  } else {
	  
	  if(empty($_POST) === false && empty($errors) === true) {
		// Zarejestruj użytkownika	
			$register_data = array(
			'username'		 => $_POST['username'],
			'password'		 => $_POST['password'],
			'first_name'	 => $_POST['first_name'],
			'last_name'		 => $_POST['last_name'],
			'email' 		 => $_POST['email'],
			);
			register_user($register_data);
			//przekierowanie
			header('Location: register.php?success');
			exit();
	  }
	  else if(empty($errors) === false){
		  //Błędy
		  echo output_errors($errors);
		  
	  }
	  
	  ?>
      <form action="" method="post">
		<ul>
			<li> 
				Nazwa użytkownika:<br>
				<input type="text" name="username">
			</li>
			<li> 
				Hasło:<br>
				<input type="password" name="password">
			</li>
			<li> 
				Powtórz hasło:<br>
				<input type="password" name="password_again">
			</li>
			<li> 
				Imię:<br>
				<input type="text" name="first_name">
				
			</li>
			<li> 
				Nazwisko:<br>
				<input type="text" name="last_name">	
			</li>
			<li> 
				Email:<br>
				<input type="text" name="email">	
			</li>
			<li>
				<input type="submit" value="Zarejestruj">
			</li>
		</ul>
	  </form>
<?php 
	  }
include 'includes/overall/footer.php'; //stopka z linkami ?>