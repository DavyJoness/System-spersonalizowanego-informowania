<?php 
include 'core/init.php';
include 'includes/overall/header.php'; //naglowek z linkami do niego
include('db_connect.php');

function createForm($nr_index='',$error='') { //Tworzy nowy formularz?>


    <!DOCTYPE html>
    <html>
        <head>
            <title>
			</title>
            <meta charset="UTF-8" /> 
        </head>
        <body>
        
            <h1>
            <?php if($error != '') {
                echo "<div style='color:red; padding: 5px'>" . $error . "</div>"; //Komunikat o błędzie
            } ?>
			
            <form action="" method="post">
                <div>
                        <p> Numer indeksu: <?php  echo $nr_index;  ?></p>
                   
                    <!-- Edycja name oraz nazwisko -->
                    <p><label>Podaj nowe hasło: </label> <br>
					<input type="password" name="pass" value="" /></p>
                    <p><label>Powtórz hasło: </label> <br>
					<input type="password" name="passreply" value="" /></p>
					<input type="hidden" value="1" name="submit">
					<input type="hidden" value="<?php  echo $nr_index;  ?>" name="nr_index">
                    <input type="submit" name="button" value="Zmień" /> <!-- Przesłanie zmian -->
                    
                </div>
            </form>
        </body>
    </html>

<?php }

    if(isset($_POST['submit'])){

        if(is_numeric($_POST['nr_index'])){
			
            $nr_index = $_POST['nr_index'];
            $pass1 = htmlentities($_POST['pass'], ENT_QUOTES); //Walidacja
            $pass2 = htmlentities($_POST['passreply'], ENT_QUOTES);
			
			$dbString = "";
			
            if($pass1 == '' || $pass2 == '' || $pass1 != $pass2){
                $error = 'Źle podałeś nowe hasło';
                //createForm($nr_index,$error);
            } else {
            	$pass1 = md5($pass1);
                if($stmt = $mysqli->prepare("update konto_student set pass = ? where nr_index = ?")){
                    $stmt->bind_param("si",$pass1 ,$nr_index);
                    $stmt->execute();
                    $stmt->close();
					echo "Poprawnie zmieniono hasło studentowi ".$nr_index;
                } else {
                    echo "Błąd zapytania";
                }
                
                header("Refresh:2; student.php");
            }
            
        }
    } else {
        if(is_numeric($_GET['nr_index']) && $_GET['nr_index'] > 0 ){
            
            $nr_index = $_GET['nr_index'];
            
            createForm($nr_index,NULL);
                 
        } else {
        	echo "smrut";
            //header("Location: student.php");
        }
    }
    


$mysqli->close();

?>
 <?php include 'includes/overall/footer.php'; //stopka z linkami ?>