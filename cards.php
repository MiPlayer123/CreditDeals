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

if (time()-$_SESSION['timestamp']>$_SESSION['idletime']){
	header('Location: logout.php');
}else{
    $_SESSION['timestamp']=time();
}

unset($_SESSION['record']);
unset($_SESSION['recordC']);

$_SESSION['count'] = 1;
			//$_SESSION['record'][$_SESSION['count']] = array();
			
$stmt = $con->prepare("SELECT cardName, cardnumber, cardid, Description FROM creditcards where id = ?");
$stmt->bind_param('i', $_SESSION['id']);
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
$stmt->bind_param('i', $_SESSION['id']);
$stmt->execute();
$resultC = $stmt->get_result();

while($row = $resultC->fetch_assoc()) {
	$_SESSION['recordC'][$_SESSION['count']]['card']= $row["card"];
	$_SESSION['recordC'][$_SESSION['count']]['deal']= $row["deal"];
	$_SESSION['recordC'][$_SESSION['count']]['customid']= $row["customid"];
	$_SESSION['count'] = $_SESSION['count'] + 1;
	//echo $_SESSION['count'];
}

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Edit Cards - Credit Deals</title>
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
			
			if (!array_key_exists('CardAdded', $_SESSION))
				$_SESSION['CardAdded'] = FALSE;
			if ($_SESSION['CardAdded'] == TRUE)
			{
				echo '<link rel="icon" href="icon.ico">';
				echo '<div class="success"><span class="closebtn" onclick="this.parentElement.style.display=\'none\';">&times;</span><strong>Success!</strong> Card added sucessfully</div>';
				$_SESSION['CardAdded'] = FALSE;
			}
			
			if (!array_key_exists('CardRemoved', $_SESSION))
				$_SESSION['CardRemoved'] = FALSE;
			if ($_SESSION['CardRemoved'] == TRUE)
			{
				echo '<link rel="icon" href="icon.ico">';
				echo '<div class="success"><span class="closebtn" onclick="this.parentElement.style.display=\'none\';">&times;</span><strong>Success!</strong> Card removed sucessfully</div>';
				$_SESSION['CardRemoved'] = FALSE;
			}
			
			if (!array_key_exists('DealAdded', $_SESSION))
				$_SESSION['DealAdded'] = FALSE;
			if ($_SESSION['DealAdded'] == TRUE)
			{
				echo '<link rel="icon" href="icon.ico">';
				echo '<div class="success"><span class="closebtn" onclick="this.parentElement.style.display=\'none\';">&times;</span><strong>Success!</strong> Deal added sucessfully</div>';
				$_SESSION['DealAdded'] = FALSE;
			}

			if (!array_key_exists('DealRemoved', $_SESSION))
				$_SESSION['DealRemoved'] = FALSE;
			if ($_SESSION['DealRemoved'] == TRUE)
			{
				echo '<link rel="icon" href="icon.ico">';
				echo '<div class="success"><span class="closebtn" onclick="this.parentElement.style.display=\'none\';">&times;</span><strong>Success!</strong> Deal removed sucessfully</div>';
				$_SESSION['DealRemoved'] = FALSE;
			}
		?>
		
		<div class="content">
			<h2>Edit Cards</h2>
			<div>
			<h3>Add Credit Card Information</h3>
				<div class ="forms">
					<form action="add.php" method="post" autocomplete="off">
						<input type="text" name="CreditCardName" id="CreditCardName" placeholder="Credit Card Name" />
						<input type="text" name="CreditCardNumber" id="CreditCardNumber" placeholder="Credit Card Number" />
						<input type="text" name="Description" id="Description" placeholder="Description (Optional)" />
						<p class="left-text">Card Type:</p>
						<select id="CardType" name="CardType" class="custom-select">
						<?php
						$stmt = "SELECT Typeid, Type FROM cardtype";
						if($result = $con->query($stmt)) {
							while($row = $result->fetch_assoc()) {
								echo'<option value="'.$row["Typeid"].'">'. $row["Type"].'</option>';
							}
						}
						?>
						</select>
						<input type="submit" value="Add Card" />
					</form>
				</div>
					
				<p> </p>
				<h3>Remove Cards</h3>
				<div class ="forms">
					<form action="remove.php" method="post" autocomplete="off">
					<select id="Card" name="Card" class="custom-select">
						<option value='-1' >None</option>
						<?php							
							$id = $_SESSION['id'];
							$stmt = "SELECT creditid, cardName FROM creditcards WHERE id='$id'";
							if($result = $con->query($stmt)) {
								while($row = $result->fetch_assoc()) {
									echo'<option value="'.$row["creditid"].'">'. $row["cardName"].'</option>';
								}
							}
						?>
						</select>
						<input type="submit" value="Remove Card" />
					</form>
				</div>
				
				<p></p>
				<h3>Custom Deals</h3>
				<div class ="forms">
					<form action="addcustom.php" method="post" autocomplete="off">
					<p class="left-text">Card Name:</p>
						<select id="CreditCardName" name="CreditCardName" class="custom-select">
							<option value='Other' >Other</option>
							<?php							
							$id = $_SESSION['id'];
							$stmt = "SELECT cardName FROM creditcards WHERE id='$id'";
							if($result = $con->query($stmt)) {
								while($row = $result->fetch_assoc()) {
									echo'<option value="'.$row["cardName"].'">'. $row["cardName"].'</option>';
								}
							}
							?>
						</select>
						<input type="text" name="Deal" id="Deal" placeholder="Deal description" />
						</select>
						<input type="submit" value="Add Deal" />
					</form>
				</div>
				
				<p> </p>
				<h3>Remove Deals</h3>
				<div class ="forms">
					<form action="removedeals.php" method="post" autocomplete="off">
					<select id="Deal" name="Deal" class="custom-select">
						<option value=-1>None</option>
						<?php
							$id = $_SESSION['id'];
							$stmt = "SELECT customid, deal FROM customcards  WHERE id='$id'";
							if($result = $con->query($stmt)) {
								while($row = $result->fetch_assoc()) {
									echo'<option value="'.$row["customid"].'">'. $row["deal"].'</option>';
								}
							}
						?>
						</select>
						<input type="submit" value="Remove Deal" />
					</form>
				</div>
				
			</div>
		</div>
	</body>
</html>