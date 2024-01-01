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
if (!isset($_POST['username'])) {
	// Could not get the data that should have been sent.
	exit('Please complete the registration form!');
}
// Make sure the submitted registration values are not empty.
if (empty($_POST['username'])) {
	// One or more values are empty.
	exit('Please complete the registration form');
}

// Username must contain only characters and numbers.
if (preg_match('/[A-Za-z0-9]+/', $_POST['username']) == 0) {
    $_SESSION['errortitle'] = "Username";
	$_SESSION['errormsg'] = " is not valid";
	$_SESSION['error']=TRUE;
	echo "<script>window.location.href='/creditdeals/profile.php';</script>";
}
$id = $_SESSION['id'];
$username = $_POST['username'];

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
		header('Location: profile.php');
		echo "<script>window.location.href='/creditdeals/profile.php';</script>";
	} else {
		// Username doesnt exists, insert new account
		if ($stmt = $con->query("UPDATE accounts SET username='$username' WHERE id = '$id'")) {
			// We do not want to expose passwords in our database, so hash the password and use password_verify when a user logs in.			
			$_SESSION['changed'] = TRUE;
			$_SESSION['error']=False;
			$_SESSION['username'] = $username;
            echo " <script>window.location.href='/creditdeals/profile.php';</script>";
			echo "worked";
				
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
