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

$stmt = $con->prepare('SELECT password FROM accounts WHERE id = ?');
// In this case we can use the account ID to get the account info.
$stmt->bind_param('i', $_SESSION['id']);
$stmt->execute();
$stmt->bind_result($password);
$stmt->fetch();

// Now we check if the data was submitted, isset() function will check if the data exists.
if (!isset($_POST['password'])) {
	// Could not get the data that should have been sent.
	exit('Please complete the registration form!');
}
// Make sure the submitted registration values are not empty.
if (empty($_POST['password'])) {
	// One or more values are empty.
	exit('Please complete the registration form');
}

if(!password_verify($_POST['passwordO'], $password)){
	$_SESSION['errortitle'] = "Existing password";
	$_SESSION['errormsg'] = " does not match";
	$_SESSION['error']=TRUE;
	echo "<script>window.location.href='/creditdeals/profile.php';</script>";
}


else if (password_verify($_POST['password'], $password)) {
    $_SESSION['errortitle'] = "Password";
	$_SESSION['errormsg'] = " is not different";
	$_SESSION['error']=TRUE;
	echo "<script>window.location.href='/creditdeals/profile.php';</script>";
}

else if($_POST['password']!=$_POST['passwordC']) {
	$_SESSION['errortitle'] = "New Passwords";
	$_SESSION['errormsg'] = " do not match";
	$_SESSION['error']=TRUE;
	echo "<script>window.location.href='/creditdeals/profile.php';</script>";
}

else if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5) {
	$_SESSION['errortitle'] = "Password";
	$_SESSION['errormsg'] = " must be between 5 and 20 characters long";
	$_SESSION['error']=TRUE;
	echo "<script>window.location.href='/creditdeals/profile.php';</script>";
}

$id = $_SESSION['id'];
$pass = password_hash($_POST['password'], PASSWORD_DEFAULT);


if ($con->query("UPDATE accounts SET password='$pass' WHERE id = '$id'")) {
	// We do not want to expose passwords in our database, so hash the password and use password_verify when a user logs in.			
	$_SESSION['changed'] = TRUE;
	$_SESSION['error']=False;
	echo " <script>window.location.href='/creditdeals/profile.php';</script>";
	echo "worked";
		
} else {
	// Something is wrong with the sql statement, check to make sure accounts table exists with all 3 fields.
	echo 'Could not prepare statement!';
}
?>
