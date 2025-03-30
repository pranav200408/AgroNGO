<?php
include('includes/config.php');

	 
	 	 
	 
	 
		 $sql = "UPDATE dire SET dir='3' WHERE id=1";
$query = $dbh -> prepare($sql);
$query->execute();
	 

?>
<?php
header( "Location: manage-users.php");
exit ;
?>