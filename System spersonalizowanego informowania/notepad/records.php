<?php 
include 'core/init.php';
include 'includes/overall/header.php'; //naglowek z linkami do niego
include('db_connect.php');

function createForm($p_name='',$p_category='',$p_nr_spr='',$error='',$id='') { //Tworzy nowy formularz?>


    <!DOCTYPE html>
    <html>
        <head>
            <title><?php if($id != '') { echo "Edytuj rekord"; } 
			
						 else { echo "Dodaj rekord"; } ?>
			</title>
            <meta charset="UTF-8" /> 
        </head>
        <body>
        
            <h1><?php if($id != '') { echo "Edytuj rekord"; } 
			
					  else { echo "Dodaj rekord"; } ?></h1>
            
            <?php if($error != '') {
                echo "<div style='color:red; padding: 5px'>" . $error . "</div>"; //Komunikat o błędzie
            } ?>
            
            <form action="" method="post">
                <div>
                    <?php if($id != '') { //Jeśli wartość nie jest pusta to mamy tryb edycji?>
                        <input type="hidden" name="id" value="<?php echo $id; 
						//Ukrycie pola edycji ID, aby było niewidoczne?>" />
                        <p> ID: <?php echo $id; ?></p>
                    <?php } ?>
                    <!-- Edycja name oraz category -->
                    <p><label>Indeks: </label> <br>
					<input type="text" name="name" value="<?php echo $p_name; ?>" /></p>
                    <p><label>Ocena: </label> <br>
					<input type="text" name="category" value="<?php echo $p_category; ?>" /></p>
					<p><label>Nr sprawdzianu: </label> <br>
					<input type="text" name="nr_spr" value="<?php echo $p_nr_spr; ?>" /></p>
                    <input type="submit" name="submit" value="Wyslij" /> <!-- Przesłanie zmian -->
                    
                </div>
            </form>
        
        </body>
    </html>

<?php }

if(isset($_GET['id'])){
    /* tryb edycji */
    if(isset($_POST['submit'])){
       
        if(is_numeric($_POST['id'])){
            $id = $_POST['id'];
            $name = htmlentities($_POST['name'], ENT_QUOTES); //Walidacja
            $category = htmlentities($_POST['category'], ENT_QUOTES);
            $nr_spr = htmlentities($_POST['nr_spr'], ENT_QUOTES);
			
            if($name == '' || $category == '' || $nr_spr ==''){
                $error = 'Wypełnij wszystkie pola';
                createForm($name,$category,$nr_spr,$error,$id);
            } else {
                if($stmt = $mysqli->prepare("UPDATE products SET name = ?, category = ?, nr_spr = ? WHERE id = ?")){
                    $stmt->bind_param("ssii",$name,$category,$nr_spr,$id);
                    $stmt->execute();
                    $stmt->close();
                } else {
                    echo "Błąd zapytania";
                }
                
                header("Location: database.php");
            }
            
        }
        
    } else {
        if(is_numeric($_GET['id']) && $_GET['id'] > 0 ){
            
            $id = $_GET['id'];
            
            if($stmt = $mysqli->prepare("SELECT * FROM products WHERE id = ?")){
                $stmt->bind_param("i",$id);
                $stmt->execute();
                $stmt->bind_result($id,$name,$category,$nr_spr);
                $stmt->fetch();
                createForm($name,$category,$nr_spr,NULL,$id);
                $stmt->close();
            } else {
                echo "Błąd zapytania";
            }
            
        } else {
            header("Location: database.php");
        }
    }
    
} else {
    /*Tryb nowego rekordu*/
    
    if(isset($_POST['submit'])){
        
        $name = htmlentities($_POST['name'], ENT_QUOTES); 
        $category = htmlentities($_POST['category'], ENT_QUOTES);
        $nr_spr = htmlentities($_POST['nr_spr'], ENT_QUOTES);
		
        if($name == '' || $category == '' || $nr_spr == ''){
            $error = 'Wypełnij wszystkie pola';
            createForm($name,$category,$nr_spr,$error);
        } else {
            if($stmt = $mysqli->prepare("INSERT products (name,category,nr_spr) VALUES (?,?,?)")){
                $stmt->bind_param("ssi",$name,$category,$nr_spr);
                $stmt->execute();
                $stmt->close();
            } else {
                echo "Błąd zapytania";
            }
            
            header("Location: database.php");
        }
        
    } else {
        createForm();
    }
    
}

$mysqli->close();

?>
 <?php include 'includes/overall/footer.php'; //stopka z linkami ?>