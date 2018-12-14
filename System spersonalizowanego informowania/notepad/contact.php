<?php 

//W pliku menu trzeba podmienic nazwy jezeli chcemy zmienic nazwe pliku
include 'core/init.php';
include 'includes/overall/header.php'; //naglowek z linkami do niego?>
      <h1>Kontakt: </h1>
	  </br> 
	 <?php
if(empty($_POST['submit'])){
?>
<form action="contact.php" method="post"> <!--nazwa strony na której znajduje się formularz-->
Imię i Nazwisko:<br />
<input type="text" name="imienazwisko" style="width:300px;"/><br />
E-Mail:<br />
<input type="text" name="email" style="width:300px;"/><br />
Treść wiadomości:<br />
<textarea name="trescwiadomosci" cols="30" rows="6" style="width:300px;"></textarea><br />
<input type="submit" name="submit" value="Wyślij formularz"/> 
<input type="reset" value="Wyczyść"/>
</form>
<?php
/*sprawdzenie wypełnienia wszystkich pól*/
}elseif(!empty($_POST['imienazwisko']) && !empty($_POST['email']) && !empty($_POST['trescwiadomosci'])){
/* Funkcja sprawdzająca poprawność E-Maila */
function SprawdzEmail($email) {
 if (!eregi("^[_.0-9a-z-]+@([0-9a-z][0-9a-z-]+.)+[a-z]{2,4}$" , $email)){
  return false;
 }
 return true;
}
if(SprawdzEmail($_POST['email'])){
/* Tworzymy szkielet wysyłanej wiadomości */
$adresemail='adis06@o2.pl'; /* Wpisz swój adres e-mail */

$charset = 'utf-8';
$wiadomosc="Od: $_POST[imienazwisko] ($_POST[email])\n\n$_POST[trescwiadomosci]";
$nadawca="From: $_POST[email]";
@mail($adresemail, "Formularz kontaktowy z Serwisu spersonalizowanego informowania.", "$wiadomosc", "$nadawca");
echo "<span style=\"color: #00D800; font-weight: bold; \">Dziękujemy, formularz został wysłany.</span>";
}else{ echo "<span style=\"color: #FF0000; text-align: center; font-weight: bold;\">Wprowadzony adres E-Mail jest niepoprawny!</span>"; }
}else{ echo "<span style=\"color: #FF0000; text-align: center; font-weight: bold;\">Cofnij i wypełnij wszystkie pola formularza!</span>"; }
?>
</br>
	  <?php
	  if(isset($_SESSION['user_id'])){
		  if($user_data['type'] == 0){
		  echo 'Jesteś zalogowany'; }
		  else {
			  echo 'Jesteś zalogowany jako administrator.';
		  }
	  } else {
		    echo 'Nie jesteś zalogowany.';      	  
	  } ?>
	  
	  
	  <?php include 'includes/overall/footer.php'; //stopka z linkami ?>