<?php 

//W pliku menu trzeba podmienic nazwy jezeli chcemy zmienic nazwe pliku
include 'core/init.php';
protect_page(); //funkcja chroniąca strone przed osobami niezalogowanymi.
admin_protect();
include 'includes/overall/header.php'; //naglowek z linkami do niego?>
<!--------------------------------------------------------------------------------------->
	 <h1>Lista rekordów</h1>
    
    <?php
    
        include('db_connect.php'); //podpięcie dokumentu db_connect
        
        if($result = $mysqli->query("SELECT * FROM grupa ORDER BY id_grupa")){ //definiujemy zapytanie
            
            if($result->num_rows > 0){ //Jeżeli liczba większa od 0 to generujemy tabele
                
                echo "<table border='1' cellpadding='10'>";
                
                echo "<tr><th>Kierunek, rok rozpoczęcia, grupa </th></tr>";
                
                while($row = $result->fetch_object()){ //Generowanie 1 wiersza z 3 kolumnami
                    echo "<tr>";
                   // echo "<td>" . $row->id_grupa. "</td>";
                    echo "<td>" . $row->nazwa . "</td>";
                    echo "<td><a href='delete_group.php?id_grupa=". $row->id_grupa . "'>Usuń</a></td>";
                    echo "</tr>";
                }
                
                echo "</table>";
                
            } else {
                echo "Brak rekordów";
            }
            
        } else {
            echo "Błąd: " . $mysqli->error;
        }
        
        $mysqli->close();
    
    ?>
    <a href="records_group.php">Dodaj nowy wynik</a>  <br>
	
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