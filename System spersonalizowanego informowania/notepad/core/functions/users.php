<?php

function update_user($update_data){
	global $session_user_id;
	$update = array();
	array_walk($register_data, 'array_sanitize');
	
	foreach($update_data as $field=>$data) {
		$update[] = '`' . $field . '` = \'' .$data . '\'';
	}
	
	mysql_query("UPDATE `users` SET" . implode(', ', $update) . "WHERE `user_id` = $session_user_id") or die(mysql_error());
}
	
function recover($mode, $email){
	$mode = sanitize($mode);
	$email = sanitize($email);
	
	$user_data = user_data(user_id_from_email($email), 'user_id', 'first_name', 'username');
	
	if($mode == 'username') {
		email($email, 'Twoja nazwa użytkownika',"Witaj ".$user_data['first_name'].",\n\nTwoja nazwa uzytkownika to: ". $user_data['username']);
		

	}else if($mode == 'password') {
		$generated_password = substr(md5(rand(999, 99999)),0,8);
		change_password($user_data['user_id'], $generated_password);
		email($email, 'Odzyskiwanie hasła',"Witaj ".$user_data['first_name'].",\n\nTwoje nowe hasło to: ". $generated_password);
	}
}
function is_admin($user_id) {
	$user_id = (int)$user_id;
	return (mysql_result(mysql_query("SELECT COUNT ('user_id') FROM 'users' WHERE 'user_id' = $user_id AND 'type' = 1"), 0) == 1) ? true : false;
}

function change_password($user_id, $password){
	$user_id=(int)$user_id;
	$password = md5($password);
	
	mysql_query("UPDATE users SET password = '$password' WHERE user_id =$user_id");
}
function register_user($register_data){
	array_walk($register_data, 'array_sanitize');
	$register_data['password'] = md5($register_data['password']); //zakodowanie hasla md5
	
	$fields = '`' . implode('`, `', array_keys($register_data)) . '`';
	$data = '\'' . implode('\', \'', $register_data) . '\'';
	
	mysql_query("INSERT INTO users ($fields) VALUES ($data)");
}


function user_count(){
	return mysql_result(mysql_query("SELECT COUNT(user_id) FROM users "), 0);
}

function user_data($user_id){
	$data = array();
	$user_id = (int)$user_id;
	
	$func_num_args = func_num_args();
	$func_get_args = func_get_args();
	
	if($func_num_args > 1) {
		unset($func_get_args[0]);
		
		$fields = '`'. implode('`, `', $func_get_args) . '`';
		$data = mysql_fetch_assoc(mysql_query("SELECT $fields FROM users WHERE user_id = $user_id"));
		return $data;
	}
}
function logged_in(){
	return (isset($_SESSION['user_id'])) ? true : false;
}
function user_exists($username) {
	$username = sanitize($username);
//	$query = mysqli_query("SELECT COUNT(user_id) FROM users WHERE username = '$username'");
//	return (mysqli_result($query, 0) == 1) ? true : false;
	
return (mysql_result(mysql_query("SELECT COUNT(user_id) FROM users WHERE username = '$username'"),0) == 1)? true : false;
}

function email_exists($email) {
	$email = sanitize($email);
//	$query = mysqli_query("SELECT COUNT(user_id) FROM users WHERE username = '$username'");
//	return (mysqli_result($query, 0) == 1) ? true : false;
	
return (mysql_result(mysql_query("SELECT COUNT(user_id) FROM users WHERE email = '$email'"),0) == 1)? true : false;
}

function user_id_from_username($username){
	$username = sanitize($username);
	return mysql_result(mysql_query("SELECT user_id FROM users WHERE username = '$username'"), 0, 'user_id');
}

function user_id_from_email($email){
	$email = sanitize($email);
	return mysql_result(mysql_query("SELECT user_id FROM users WHERE email = '$email'"), 0, 'user_id');
}

function login($username, $password) {
	$user_id = user_id_from_username($username);
	
	$username = sanitize($username);
	$password = md5($password);
	
	return (mysql_result(mysql_query("SELECT COUNT('user_id') FROM users WHERE username = '$username' AND password = '$password'"), 0) == 1) ? $user_id : false;
}

function check_name_exist($name, $flag)
{
	switch($flag)
	{
		case 0:
			if(mysql_result(mysql_query("SELECT COUNT('nazwa') FROM grupa WHERE nazwa LIKE '$name'"), 0) == 0)
				{
					return true;
				}
				else {
					return false;
				}
			break;
		case 1:
			if(mysql_result(mysql_query("SELECT COUNT('nazwa') FROM przedmiot WHERE nazwa LIKE '$name'"), 0) == 0)
				{
					return true;
				}
				else {
					return false;
				}
			break;
	}
}

function pol_letter_parser($word)
{
	$ocute = "&oacute;";
	if(strpos($ocute, $word))
	{
		$parts = explode($word, $ocute);
		$word = implode("ó", $parts);
		return $word;
	}
	return $word;
}
function commit_rights($id_wyk, $id_spr)
{
	if(mysql_result(mysql_query("SELECT * FROM sprawdzian WHERE id_sprawdzian=$id_spr AND id_wyk=$id_wyk"), 0) == 0)
	return true;
	else
	return false;
}


function ocena($ocena){
	switch ($ocena) {
		case 20:
			return "Niedostateczny (2,0)";
		case 30:
			return "Dostateczny (3,0)";
		case 35:
			return "Dostateczny plus (3,5)";
		case 40:
			return "Dobry (4,0)";
		case 45:
			return "Dorby plus (4,5)";
		case 50:
			return "Bardzo dobry (5,0)";	
		case 0:
			return "Brak oceny";
		case 10:
			return "Zaliczony";
		case 5:
			return "Niezaliczony";
		case 1:
			return "Nieobecny";	
		default:
			return "Brak oceny";
	}	
}

function sprawdz_bledy()
{
  if ($_FILES['plik']['error'] > 0)
  {
    echo 'problem: ';
    switch ($_FILES['plik']['error'])
    {

      case 1: {echo 'Rozmiar pliku jest zbyt duży.'; break;} 

      case 2: {echo 'Rozmiar pliku jest zbyt duży.'; break;}

      case 3: {echo 'Plik wysłany tylko częściowo.'; break;}

      case 4: {echo 'Nie wysłano żadnego pliku.'; break;}

      default: {echo 'Wystąpił błąd podczas wysyłania.';
        break;}
    }
    return false;
  }
  return true;
}

function sprawdz_typ()
{
	if ($_FILES['plik']['type'] != 'text/xml')
		return false;
	return true;
}

function zapisz_plik()
{
  $lokalizacja = 'temp/kopie/'.$_SESSION['user_id'].'.xml'; //'.$_SESSION['user_id'].'
	
  if(is_uploaded_file($_FILES['plik']['tmp_name']))
  {
    if(!move_uploaded_file($_FILES['plik']['tmp_name'], $lokalizacja))
    {
      echo 'problem: Nie udało się skopiować pliku do katalogu.';
        return false;  
    }
  }
  else
  {
    echo 'problem: Możliwy atak podczas przesyłania pliku.';
	echo 'Plik nie został zapisany.';
    return false;
  }
  return $lokalizacja;
}

function sprawdzDate($data)
{
	if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$data))
    {
        return true;
    }else{
        return false;
    }
}

?>