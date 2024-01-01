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
// Now we check if the data was submitted, isset() function will check if the data exists.
if (!isset($_POST['username'], $_POST['password'], $_POST['firstname'], $_POST['lastname'], $_POST['email'])) {
	// Could not get the data that should have been sent.
	$_SESSION['errortitle'] = "Incomplete";
	$_SESSION['errormsg'] = " registration form";
	$_SESSION['error']=TRUE;
	echo "<script>window.location.href='/creditdeals/register.php';</script>";
}
// Make sure the submitted registration values are not empty.
if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['firstname']) || empty($_POST['lastname']) || empty($_POST['email'])) {
	// One or more values are empty.
	$_SESSION['errortitle'] = "Incomplete";
	$_SESSION['errormsg'] = " registration form";
	$_SESSION['error']=TRUE;
	echo "<script>window.location.href='/creditdeals/register.php';</script>";
}
// Check to see if the email is valid.
if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
	$_SESSION['errortitle'] = "Email";
	$_SESSION['errormsg'] = " is not valid";
	$_SESSION['error']=TRUE;
	echo "<script>window.location.href='/creditdeals/register.php';</script>";
}
// Username must contain only characters and numbers.
if (preg_match('/[A-Za-z0-9]+/', $_POST['username']) == 0) {
	$_SESSION['errortitle'] = "Username";
	$_SESSION['errormsg'] = " is not valid";
	$_SESSION['error']=TRUE;
	echo "<script>window.location.href='/creditdeals/register.php';</script>";
}
// Password must be between 5 and 20 characters long.
if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5) {
	$_SESSION['errortitle'] = "Password";
	$_SESSION['errormsg'] = " must be between 5 and 20 characters long";
	$_SESSION['error']=TRUE;
	echo "<script>alert('Password must be between 5 and 20 characters long'); window.location.href='/creditdeals/register.php';</script>";
}
// We need to check if the account with that username exists.
if ($stmt = $con->prepare('SELECT id, password FROM accounts WHERE username = ?')) {
	// Bind parameters (s = string, i = int, b = blob, etc), hash the password using the PHP password_hash function.
	$stmt->bind_param('s', $_POST['username']);
	$stmt->execute();
	$stmt->store_result();
	// Store the result so we can check if the account exists in the database.
	if ($stmt->num_rows > 0) {
		// Username already exists
		$_SESSION['errortitle'] = "Username";
		$_SESSION['errormsg'] = " already exists, please choose another";
		$_SESSION['error']=TRUE;
		header('Location: register.php');
		echo "<script>window.location.href='/creditdeals/register.php';</script>";
	} else {
		// Username doesnt exists, insert new account
		if ($stmt = $con->prepare('INSERT INTO accounts (username, password, email, firstname, lastname) VALUES (?, ?, ?, ?, ?)')) {
			// We do not want to expose passwords in our database, so hash the password and use password_verify when a user logs in.
			$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
			$stmt->bind_param('sssss', $_POST['username'], $password, $_POST['email'], $_POST['firstname'], $_POST['lastname']);
			$stmt->execute();
			
			$_SESSION['registered'] = TRUE;
			$_SESSION['error']=False;
            echo "
                <script>
                    window.location.href='/creditdeals/login.php';
                </script>";
			
			//echo 'You have successfully registered, you can now login!<br><a href="/login.php">Login</a>';
		} else {
			// Something is wrong with the sql statement, check to make sure accounts table exists with all 3 fields.
			echo 'Could not prepare statement!';
		}
	}
} else {
	// Something is wrong with the sql statement, check to make sure accounts table exists with all 3 fields.
	echo 'Could not prepare statement!';
}
?>
