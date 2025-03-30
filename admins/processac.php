<?php
if(isset($_POST['save']))
{	 
	 
	 $bal = $_POST['bal'];
}

$mysqli = new mysqli("localhost", "root", "", "tms"); 
  
if($mysqli === false){ 
    die("ERROR: Could not connect. " 
            . $mysqli->connect_error); 
} 
 $sql = "INSERT INTO churn (comm)
VALUES ('$bal')";

if($mysqli->query($sql) === true){ 
    echo "Records was updated successfully."; 
} else{ 
    echo "ERROR: Could not able to execute $sql. "  
                                        . $mysqli->error; 
} 
$mysqli->close(); 

?>
<?php
header( "Location: manage-users.php");
exit ;
?>
