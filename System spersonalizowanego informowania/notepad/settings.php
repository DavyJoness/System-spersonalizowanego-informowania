
<?php 
include 'core/init.php';
protect_page();
include 'includes/overall/header.php';

if(empty($_POST) === false){
	$required_fields = array('first_name','last_name','email');
	foreach($_POST as $key=>$value) {
		if(empty($value) && in_array($key, $required_fields) === true) {
			$errors[] = 'Należy wypełnić wszystkie pola!';
			break 1;
		}
	}
	if (empty($errors) === true) {
		if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false) {
			$errors[] = 'Wpisany adres email jest niepoprawny.';
		} else if(email_exists($_POST['email']) === true && $user_data['email'] !== $_POST['email'])
						{
				$errors[]= 'Przepraszamy, ale email \'' . $_POST['email']. '\' jest już w użyciu';			
							}			
			
		}
	
	}
?>
	<h1> Ustawienia </h1>
	<?php
	if(isset($_GET['success']) === true && empty($_GET['success']) === true) {
		echo 'Twoje dane zostały zaktualizowane.';
	}
	else{	
	if(empty($_POST) === false && empty($errors) === true) {
		$update_data = array(
		 'first_name'	 => $_POST['first_name'],
		 'last_name'	 => $_POST['last_name'],
		 'email'		 => $_POST['email'],
		);
		
		update_user($update_data);
		header('Location: settings.php?success');
		exit();
		
	} else if (empty($errors) === false){
		echo output_errors($errors);
	}
	
	?>
	
	
	  <form action= "" method="post">
		<ul>
			<li>
				Imię:<br>
				<input type="text" name="first_name" value="<?php echo $user_data['first_name']; ?>">
			</li>
			<li>
				Nazwisko:<br>
				<input type="text" name="last_name" value="<?php echo $user_data['last_name']; ?>">
			</li>
			<li>
				Email:<br>
				<input type="text" name="email" value="<?php echo $user_data['email']; ?>">
			</li>
			<li>
				<input type="submit" value="Aktualizuj">
			</li>
		</ul>
		</form>
	  
<?php
	}
	  include 'includes/overall/footer.php'; //stopka z linkami ?>
