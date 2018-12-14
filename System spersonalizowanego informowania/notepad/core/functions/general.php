<?php
function email($to, $subject, $body){
	mail($to, $subject, $body, 'From: adis06@o2.pl');
}
//Ta funkcja przekierowuje na strone glowna jezeli ktos jest zalogowany i sprobuje sie zarejestrowac.
function logged_in_redirect() {
	if (logged_in() === true){
		header('Location: index.php');
	}
}
function protect_page() {
	if (logged_in() === false) {
		header('Location: protected.php');
		exit();
	}
}
function admin_protect() {
	global $user_data;
	if($user_data['type'] == 0)
	//  if(is_admin($user_data['user_id']) === false)
		{
		header('Location: index.php');
		exit();
}
}

function lecturer_protect() {
	global $user_data;
	if($user_data['type'] == 1)
	//  if(is_admin($user_data['user_id']) === false)
		{
		header('Location: index.php');
		exit();
}
}

function array_sanitize(&$item){
	$item = mysql_real_escape_string($item);
}
function sanitize($data){
	//return mysqli_real_escape_string($data);
	return mysql_real_escape_string($data);
}

function output_errors($errors){
	return '<ul><li>' . implode('</li><li>', $errors) . '</li></ul>';
	//$output = array();
	//foreach($errors as $error) {
	//	$output[] = '<li>'. $error . '</li>';
//	}
//	return '<ul>' . implode('', $output).' </ul>';
}

?>