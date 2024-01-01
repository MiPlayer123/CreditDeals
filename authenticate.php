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
// Now we check if the data from the login form was submitted, isset() will check if the data exists.
if (!isset($_POST['username'], $_POST['password'])) {
	// Could not get the data that should have been sent.
	exit('Please fill both the username and password field!');
}
// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
if ($stmt = $con->prepare('SELECT id, password, firstname, lastname FROM accounts WHERE username = ?')) {
	// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
	$stmt->bind_param('s', $_POST['username']);
	$stmt->execute();
	// Store the result so we can check if the account exists in the database.
	$stmt->store_result();
	// If the username exiusts
	if ($stmt->num_rows == 1) {
		$stmt->bind_result($id, $password, $firstname, $lastname);
		$stmt->fetch();
		// Account exists, now we verify the password.
		// Note: remember to use password_hash in your registration file to store the hashed passwords.
		if (password_verify($_POST['password'], $password)) { //bor both plaintext and encrypted passwords
			// Verification success! User has loggedin!
			// Create sessions so we know the user is logged in, they basically act like cookies but remember the data on the server.
			session_regenerate_id();
			
			$_SESSION['loggedin'] = TRUE;
			$_SESSION['username'] = $_POST['username'];
			$_SESSION['id'] = $id;
			$_SESSION['firstname'] = $firstname;
			$_SESSION['lastname'] = $lastname;
			
			$_SESSION['count'] = 1;
			//$_SESSION['record'][$_SESSION['count']] = array();
			
			$stmt = $con->prepare("SELECT cardName, cardnumber, cardid, Description FROM creditcards where id = ?");
			$stmt->bind_param('i', $id);
			$stmt->execute();
			$result = $stmt->get_result();
			
			while($row = $result->fetch_assoc()) {
				$_SESSION['record'][$_SESSION['count']]['cardName']= $row["cardName"];
				$_SESSION['record'][$_SESSION['count']]['Description']= $row["Description"];
				$_SESSION['record'][$_SESSION['count']]['cardNumber']= $row["cardnumber"];
				$_SESSION['record'][$_SESSION['count']]['cardid']= $row["cardid"];
				$_SESSION['count'] = $_SESSION['count'] + 1;
				//echo $_SESSION['count'];
			}
			
			
			$stmt = $con->prepare("SELECT card, deal, customid FROM customcards where id = ?");
			$stmt->bind_param('i', $id);
			$stmt->execute();
			$resultC = $stmt->get_result();
			
			while($row = $resultC->fetch_assoc()) {
				$_SESSION['recordC'][$_SESSION['count']]['card']= $row["card"];
				$_SESSION['recordC'][$_SESSION['count']]['deal']= $row["deal"];
				$_SESSION['recordC'][$_SESSION['count']]['customid']= $row["customid"];
				$_SESSION['count'] = $_SESSION['count'] + 1;
				//echo $_SESSION['count'];
			}
			/*
			foreach($_SESSION['record'] as $key => $value)
			{			
				echo $value['cardName'];
				echo $value['cardNumber'];
				echo $value['Description'];
				echo $value['cardid'];
			}
			echo "<br>";
			foreach($_SESSION['recordC'] as $key => $value)
			{			
				echo $value['card'];
				echo $value['deal'];
				echo $value['customid'];;
			}
			*/
			
			$_SESSION['timestamp']=time();  
			header('Location: home.php');
		} else {
			$_SESSION['errortitle'] = "Incorrect";
			$_SESSION['errormsg'] = "username or passowrd";
			$_SESSION['failed'] = TRUE;
			header('Location: login.php');
			//echo 'Incorrect password!';
		}
	} else {
		$_SESSION['failed'] = TRUE;
		header('Location: login.php');
		//echo 'Incorrect username!';
	}
} else {
	echo 'Could not prepare statement!';
}
?>
