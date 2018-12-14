<?php 
include 'core/init.php';
include 'includes/overall/header.php'; //naglowek z linkami do niego
include('db_connect.php');

function createForm($nr_index='',$p_sprawdzian='',$p_oceny='',$p_default_rating='', $p_data='', $error='') { //Tworzy nowy formularz?>

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="https://jquery-ui.googlecode.com/svn-history/r3982/trunk/ui/i18n/jquery.ui.datepicker-pl.js"></script>
<script>
$(function() {
  $.datepicker.setDefaults( $.datepicker.regional[ "pl" ] );
  $( "#datepicker" ).datepicker({
  	dateFormat:"yy-mm-dd"
  });
});
</script>

    <?php if($error != '') {
        echo "<div style='color:red; padding: 5px'>" . $error . "</div>"; //Komunikat o błędzie
    } ?>
	
    <form action="" method="post">
        <div>
            <p> Numer indeksu: <?php  echo $nr_index;  ?></p>

			<p><label>Ocena: </label> <br>
			<select name="id_ocena"> 
				<?php 
					foreach($p_oceny as $key => $value)
					{
						if($key == $p_default_rating)
						echo "<option value='".$key."' selected='selected'>".$value."</option>";	
						else
						echo "<option value='".$key."'>".$value."</option>";
					}
					
				?>
			</select>
			<p><label>Data dodania oceny: </label> <br>
			<input type="date" name="opt_date" id="datepicker" value="<?php echo $p_data; ?>" required="required" /></p>
			<input type="hidden" value="<?php  echo $p_sprawdzian;  ?>" name="id_sprawdzian">
			<input type="hidden" value="<?php  echo $nr_index;  ?>" name="nr_index">
            <input type="submit" name="submit" value="Wyslij" /> <!-- Przesłanie zmian -->
            
        </div>
    </form>

<?php }

    if(isset($_POST['submit'])){
        if(is_numeric($_POST['nr_index'])){
            $nr_index = $_POST['nr_index'];
            $id_sprawdzian = htmlentities($_POST['id_sprawdzian'], ENT_QUOTES);
            $id_ocena = htmlentities($_POST['id_ocena'], ENT_QUOTES);
			$data = htmlentities($_POST['opt_date'], ENT_QUOTES);
			
			$dbString = "";

            if($nr_index == '' || $id_sprawdzian == '' || $id_ocena =='' || $data == '' || !sprawdzDate($data)){
                $error = 'Wypełnij wszystkie pola';
                $oceny = array(20 => "Niedostateczny",30 => "Dostateczny",35 => "Dostateczny plus",40 => "Dobry",45 => "Dobry plus",50 => "Bardzo dobry",);
			
				createForm($nr_index,$id_sprawdzian,$oceny,$error);
            } else {
                if($stmt = $mysqli->prepare("UPDATE ocena SET ocena = ?, data = ? WHERE nr_index = ? AND id_sprawdzian = ?")){
                    $stmt->bind_param("isii",$id_ocena,$data,$nr_index,$id_sprawdzian);
                    $stmt->execute();
                    $stmt->close();
					
                } else {
                    echo "Błąd zapytania";
                }
                echo "Poprawnie dokonano edycji wpisu.";
                header("Refresh:2; mod_test.php");
            }
            $mysqli->close();
        }
    } else {
    	//sprawdzenie czy user nie przeszedl do edycji wyniku studenta, do ktorej nie ma uprawnien
		if(commit_rights($_SESSION['user_id'], $_GET['id_sprawdzian']))
		{
			echo "Błąd formularza";
			exit;
		}
		
        if(is_numeric($_GET['nr_index']) && $_GET['nr_index'] > 0  && is_numeric($_GET['id_sprawdzian']) && $_GET['id_sprawdzian'] > 0){
            
            $nr_index = $_GET['nr_index'];
            $id_sprawdzian = $_GET['id_sprawdzian'];
			$default_rating = $_GET['cu_rating'];
			$data = $_GET['data_dod'];
			$oceny = array(0 => "Brak oceny", 20 => "Niedostateczny",30 => "Dostateczny",35 => "Dostateczny plus",40 => "Dobry",45 => "Dobry plus",50 => "Bardzo dobry", 10 => "Zaliczony", 5 => "Niezaliczony", 1 => "Nieobecny",);
			
			createForm($nr_index,$id_sprawdzian,$oceny,$default_rating,$data,NULL);

            
        } else {
            header("Location: mod_test.php");
        }
    }
?>
 <?php include 'includes/overall/footer.php'; //stopka z linkami ?>