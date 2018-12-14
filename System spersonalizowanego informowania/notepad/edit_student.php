<?php 
include 'core/init.php';
include 'includes/overall/header.php'; //naglowek z linkami do niego
include('db_connect.php');

function createForm($nr_index='',$p_imie='',$p_nazwisko='',$p_listagrup='',$error='') { //Tworzy nowy formularz?>

    <?php if($error != '') {
        echo "<div style='color:red; padding: 5px'>" . $error . "</div>"; //Komunikat o błędzie
    } ?>
	
    <form action="" method="post">
        <div>
                <p> Numer indeksu: <?php  echo $nr_index;  ?></p>
           
            <!-- Edycja name oraz nazwisko -->
            <p><label>Imię: </label> <br>
			<input type="text" name="imie" value="<?php echo $p_imie; ?>" /></p>
            <p><label>Nazwisko: </label> <br>
			<input type="text" name="nazwisko" value="<?php echo $p_nazwisko; ?>" /></p>
			<p><label>Grupa: </label> <br>
			<select name="id_grupa"> 
				<?php 
					foreach($p_listagrup as $grupa)
					{
						echo "<option value='".$grupa[0]."'>".$grupa[0]."</option>";
					}
					
				?>
			</select>
			<input type="hidden" value="1" name="submit">
			<input type="hidden" value="<?php  echo $nr_index;  ?>" name="nr_index"><br><br>
            <input type="submit" name="button" value="Wyslij" /> <!-- Przesłanie zmian -->
            
        </div>
    </form>
	<a href='pass_student.php?nr_index=<?php echo $nr_index; ?>'>Zmień hasło studenta</a>

<?php }

    if(isset($_POST['submit'])){
        if(is_numeric($_POST['nr_index'])){

            $nr_index = $_POST['nr_index'];
            $imie = htmlentities($_POST['imie'], ENT_QUOTES); //Walidacja
            $nazwisko = htmlentities($_POST['nazwisko'], ENT_QUOTES);
            $id_grupa = htmlentities($_POST['id_grupa'], ENT_QUOTES);
			
			$dbString = "";
			
            if($imie == '' || $nazwisko == '' || $id_grupa ==''){
                $error = 'Wypełnij wszystkie pola';
                createForm($nr_index,$imie,$nazwisko,$id_grupa,$error);
            } else {
                if($stmt = $mysqli->prepare("call a_edytuj_studenta(?, ?, ?, ?)")){
                    $stmt->bind_param("isss",$nr_index,$imie,$nazwisko,$id_grupa);
                    $stmt->execute();
                    $stmt->close();
					
                } else {
                    echo "Błąd zapytania";
                }
                
                header("Location: student.php");
            }
            
        }
    } else {
        if(is_numeric($_GET['nr_index']) && $_GET['nr_index'] > 0 ){
            
            $nr_index = $_GET['nr_index'];
            
            if($stmt = $mysqli->prepare("SELECT nr_index, imie, nazwisko, id_grupa FROM konto_student WHERE nr_index = ?")){
                $stmt->bind_param("i",$nr_index);
                $stmt->execute();
                $stmt->bind_result($nr_index,$imie,$nazwisko,$id_grupa);
                $stmt->fetch();
				$stmt->close();
				$grupy = $mysqli->query("SELECT nazwa FROM grupa");
				$tablicagrup = $grupy->fetch_all();
				$grupy->close();
                createForm($nr_index,$imie,$nazwisko,$tablicagrup,NULL);
                
            } else {
                echo "Błąd zapytania";
            }
            
        } else {
            header("Location: student.php");
        }
    }
    


$mysqli->close();

?>
 <?php include 'includes/overall/footer.php'; //stopka z linkami ?>