<?php
include 'core/init.php';
logged_in_redirect();
//if (user_exists('adrian') === true) {
//	echo 'exists';}
//die();


if(empty($_POST) === false){
	$username = $_POST['username'];
	$password = $_POST['password'];
	
	if(empty($username) === true || empty($password) === true){
		$errors[] = 'Musisz wpisać nazwe użytkownika i hasło';
	} else if(user_exists($username) === false) {
		$errors[] = 'Taki użytkownik nie jest zarejestrowany';
	} else {
		
		if(strlen($password)>32){
			$errors[] = 'Hasło jest za długie';
		}
		
		
		$login = login($username, $password);
		if ($login === false) {
			$errors[] = 'Hasło lub login jest niepoprawne';
		}
		else {
			//sesja uzytkownika
			$_SESSION['user_id'] = $login;
			header('Location: index.php');
			exit();
			//przekierowanie do domu
			
		}
	}
}else {
		$errors[] = ' Brak otrzymancyh danych ';
	
}

include 'includes/overall/header.php';

if(empty($errors) === false) {
?>
	<h2>Próbowaliślmy Cię zalogować, ale:</h2>
	<?php
	echo output_errors($errors);
}
//markup

include 'includes/overall/footer.php';
?>