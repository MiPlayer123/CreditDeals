<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: login.html');
	exit;
}
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'creditdeals';
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
// We don't have the password or email info stored in sessions so instead we can get the results from the database.
$stmt = $con->prepare('SELECT password, email FROM accounts WHERE id = ?');
// In this case we can use the account ID to get the account info.
$stmt->bind_param('i', $_SESSION['id']);
$stmt->execute();
$stmt->bind_result($password, $email);
$stmt->fetch();

if (time()-$_SESSION['timestamp']>$_SESSION['idletime']){
	header('Location: logout.php');
}else{
    $_SESSION['timestamp']=time();
}

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Profile - Credit Deals</title>
		<link href="css.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
		<link rel="icon" href="icon.ico">
	</head>
	<body class="loggedin">
		<nav class="navtop">
			<div>
				<h1>Credit Deals</h1>
				<a href="home.php"><i class="fas fa-home"></i>Home</a>
				<a href="cards.php"><i class="fas fa-credit-card"></i>Edit Cards</a>
				<a href="profile.php"><i class="fas fa-user-circle"></i>Profile</a>
				<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
			</div>
		</nav>
		<?php
			if (!array_key_exists('error', $_SESSION))
				$_SESSION['error'] = FALSE;
			if ($_SESSION['error'] == TRUE)
			{
				$_SESSION['error'] = FALSE;
				echo '<link rel="icon" href="icon.ico">';
				echo "<div class=\"alert\">";
				echo '<span class="closebtn" onclick="this.parentElement.style.display=\'none\';">&times;</span>';
				echo "<strong>".$_SESSION['errortitle']."</strong> ".$_SESSION['errormsg']."</div>";
			}
			
			if (!array_key_exists('changed', $_SESSION))
				$_SESSION['changed'] = FALSE;
			if ($_SESSION['changed'])
			{
				$_SESSION['changed'] = FALSE;
				echo '<link rel="icon" href="icon.ico">';
				echo '<div class="success"><span class="closebtn" onclick="this.parentElement.style.display=\'none\';">&times;</span><strong>Changed</strong> sucessfully!</div>';
			}
		?>
		<div class="content">
			<h2>Profile</h2>
			<div>
				<p>Your account details are below:</p>
				<table>
					<tr>
						<td>Name:</td>
						<td><?=$_SESSION['firstname']?> <?=$_SESSION['lastname']?></td>
					</tr>
					<tr>
						<td>Username:</td>
						<td><?=$_SESSION['username']?></td>
					</tr>
					<tr>
						<td>Email:</td>
						<td><?=$email?></td>
					</tr>
				</table>
				<br>
				<div class ="forms"><form method="post"><input type="submit" value="Edit Username" name="edituser"/></div></form>
				<br>
				<div class ="forms"><form method="post"><input type="submit" value="Edit Password" name="editpassword"/></div></form>
				<?php
					if(array_key_exists('edituser', $_POST)) {
						editUsername();
					}
					if(array_key_exists('editpassword', $_POST)) {
						editPass();
					}
					
					function editUsername() {		
						echo  '<br><div class ="forms"><form action="changeuser.php" method="post" autocomplete="off">
								<label for="username">
									<i class="fas fa-user"></i>
								</label>
								<input type="text" name="username" placeholder="Enter new Username" id="username" required>
								<input type="submit" value="Submit">
								</form></div>';
					
					}
					
					function editPass() {
						echo '<br><div class ="forms"><form action="changepass.php" method="post" autocomplete="off">
								<label for="username">
									<i class="fas fa-lock"></i>
								</label>
								<input type="password" name="passwordO" placeholder="Enter original Password" id="passwordO" required>
								<input type="password" name="password" placeholder="Enter new Password" id="password" required>
								<input type="password" name="passwordC"  placeholder="Confirm new Passowrd" id="passwordC" required>
								<input type="submit" value="Submit">
								</form></div>';
					}
				?>
			</div>
		</div>
	</body>
</html>
