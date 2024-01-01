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
// Make sure the submitted registration values are not empty.
if (empty($_POST['CreditCardName']) ) {
	// One or more values are empty.
	$_SESSION['errortitle'] = "Blank";
	$_SESSION['errormsg'] = " credit card Name";
	$_SESSION['error']=TRUE;
	$_SESSION['DealAdded'] = FALSE;
	echo "<script>window.location.href='/creditdeals/cards.php';</script>";
}
if (empty($_POST['Deal']) ) {
	// One or more values are empty.
	$_SESSION['errortitle'] = "Blank";
	$_SESSION['errormsg'] = " deal";
	$_SESSION['error']=TRUE;
	$_SESSION['DealAdded'] = FALSE;
	echo "<script>window.location.href='/creditdeals/cards.php';</script>";
}
else if ($stmt = $con->prepare('INSERT INTO customcards (id, card, deal) VALUES (?, ?, ?)')) {
	$stmt->bind_param('iss', $_SESSION['id'], $_POST['CreditCardName'], $_POST['Deal']);
	if(!$stmt->execute())
		echo "Failed";
	$_SESSION['DealAdded'] = TRUE;
	$_SESSION['error']=FALSE;
	echo "<script>window.location.href='/creditdeals/cards.php';</script>";
}
else{
// Something is wrong with the sql statement, check to make sure accounts table exists with all 3 fields.
echo 'Could not prepare statement!';
}
?>