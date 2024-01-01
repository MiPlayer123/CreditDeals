<?php
session_start();
// Change this to your connection info.
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'creditdeals';
// Try and connect using the info above.
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
	// If there is an error with the connection, stop the script and display the error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

if (($_POST['Card']==-1) ) {
	// One or more values are empty.
	$_SESSION['errortitle'] = "No";
	$_SESSION['errormsg'] = " credit card selected";
	$_SESSION['error']=TRUE;
	$_SESSION['CardRemoved'] = FALSE;
	echo "<script>window.location.href='/creditdeals/cards.php';</script>";
}

else if ($stmt = $con->prepare('DELETE FROM creditcards where creditid = ?')) {
	$stmt->bind_param('i', $_POST['Card']);
	if(!$stmt->execute())
		echo "Failed";
	$_SESSION['CardRemoved'] = TRUE;
	$_SESSION['error']=FALSE;
	echo "<script>window.location.href='/creditdeals/cards.php';</script>";
}
else{
// Something is wrong with the sql statement, check to make sure accounts table exists with all 3 fields.
echo 'Could not prepare statement!';
}
?>