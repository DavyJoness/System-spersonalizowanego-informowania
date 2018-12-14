
<?php 
include 'core/init.php';
protect_page();

if (empty($_POST) === false) {
		$required_fields = array('current_password', 'password', 'password_again');
		foreach($_POST as $key=>$value) {
			if(empty($value) && in_array($key, $required_fields) === true) {
				$errors[] = 'Należy wypełnić wszystkie pola!';
				break 1;
		}
				
}
	if(md5($_POST['current_password']) ===  $user_data['password']) {
		if(trim($_POST['password']) !== trim($_POST['password_again'])) //trim usuwa spacje z lewej i prawej strony
		{
				$errors[] = ' Wpisane hasła nie są jednakowe.';
		} else if(strlen($_POST['password']) < 6){
			$errors[] = 'Twoje hasło musi składać się przynajmniej z 6-iu znaków.';
		}
	} else{
		$errors[] = 'Wpisałeś błędne obecne hasło';
	}


}
include 'includes/overall/header.php'; //naglowek z linkami do niego?>
      <h1>Zmiana hasła</h1>
	  
	  <?php
	  if (isset($_GET['success']) && empty($_GET['success'])){
		  echo 'Twoje hasło zostało zmienione.';
	  }
	  else {
	  if (empty($_POST) === false && empty($errors) === true){
		  change_password($session_user_id, $_POST['password']);
			header('Location: changepassword.php?success');
		  } else if (empty($errors) === false) {
		  echo output_errors($errors);
	  }
		  ?>

	  <form action="" method="post">
		<ul>
			<li>
			Obecne hasło: <br>
			<input type="password" name="current_password">
			</li>
			<li>
						Nowe hasło: <br>
			<input type="password" name="password">
			</li>
			<li>
						Nowe hasło ponownie: <br>
			<input type="password" name="password_again">
			</li>
			<li>
			<input type="submit" value="Zmień hasło">
			</li>
		</ul>

	  
	  <?php
	  }	  include 'includes/overall/footer.php'; //stopka z linkami ?>
