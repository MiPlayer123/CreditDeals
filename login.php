<?php
session_start();

if (!array_key_exists('registered', $_SESSION))
    $_SESSION['registered'] = FALSE;
if ($_SESSION['registered'] == TRUE)
{
	echo '<link rel="icon" href="icon.ico">';
	echo '<div class="success"><strong>Success!</strong> You have successfully registered</div>';
    $_SESSION['registered'] = FALSE;
}

if (!array_key_exists('failed', $_SESSION))
    $_SESSION['failed'] = FALSE;
if ($_SESSION['failed'] == TRUE)
{
	echo '<link rel="icon" href="icon.ico">';
	echo '<div class="alert"><strong>Incorrect</strong> username or password</div>';
    $_SESSION['failed'] = FALSE;
}

/*
if (!array_key_exists('error', $_SESSION))
    $_SESSION['error'] = FALSE;
if ($_SESSION['error'] == TRUE)
{
    echo '<link rel="icon" href="icon.ico">';
	echo "<div class=\"alert\"><strong>".$_SESSION['errortitle']."</strong> ".$_SESSION['errormsg']."</div>";
    $_SESSION['failed'] = FALSE;
}*/
?>

<!DOCTYPE html>
<html>
    <head>
        <meta chaset="utf-8">
        <title>Login</title>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
        <link href="css.css" rel="stylesheet" type="text/css">
		<link rel="icon" href="icon.ico">
		<link rel="stylesheet" href="dist/css/style.css">
		<script src="https://unpkg.com/animejs@3.0.1/lib/anime.min.js"></script>
		<script src="https://unpkg.com/scrollreveal@4.0.0/dist/scrollreveal.min.js"></script>
    </head>
    <body>
	
		<div class="login">
			<h1>Login</h1>
			<div class="links">
				<a href="login.php" class="active">Login</a>
				<a href="register.php">Register</a>
			</div>
            <form action="authenticate.php" method="post">
                <label for="username">
                    <i class="fas fa-user"></i>
                </label>
                <input type="text" name="username" placeholder="Username" id="username" required>
                <label for="password">
                    <i class="fas fa-lock"></i>
                </label>
                <input type="password" name="password" placeholder="Password" id="password" required>
                <input type="submit" value="Login">
            </form>
        </div>
    </body>
</html>