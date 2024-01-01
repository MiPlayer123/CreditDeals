<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!array_key_exists('error', $_SESSION))
    $_SESSION['error'] = FALSE;
if ($_SESSION['error'] == TRUE)
{
	$_SESSION['error'] = FALSE;
    echo '<link rel="icon" href="icon.ico">';
	echo "<div class=\"alert\"><strong>".$_SESSION['errortitle']."</strong> ".$_SESSION['errormsg']."</div>";
}

?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Register</title>
		<link href="css.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
		<link rel="icon" href="icon.ico">
		<link rel="stylesheet" href="dist/css/style.css">
		<script src="https://unpkg.com/animejs@3.0.1/lib/anime.min.js"></script>
		<script src="https://unpkg.com/scrollreveal@4.0.0/dist/scrollreveal.min.js"></script>
	</head>
	<body>
		<div class="register">
			<h1>Register</h1>
			<div class="links">
				<a href="login.php">Login</a>
				<a href="register.php" class="active">Register</a>
			</div>
			<form action="registering.php" method="post" autocomplete="off"> 
			<!--register-with-activation for with email (dont work), or registration for normal!-->
				<label for="username">
					<i class="fas fa-user"></i>
				</label>
				<input type="text" name="username" placeholder="Username" id="username" required>
				<label for="password">
					<i class="fas fa-lock"></i>
				</label>
				<input type="password" name="password" placeholder="Password" id="password" required>
				<label for="firstname">
					<i class="fas fa-user-tag"></i>
				</label>
				<input type="text" name="firstname" placeholder="First Name" id="firstname" required>
				<label for="lastname">
					<i class="fas fa-user-tie"></i>
				</label>
				<input type="text" name="lastname" placeholder="Last Name" id="lastname" required>
				<label for="email">
					<i class="fas fa-envelope"></i>
				</label>
				<input type="email" name="email" placeholder="Email" id="email" required>
				<input type="submit" value="Register">
			</form>
		</div>
	</body>
</html>