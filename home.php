<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: login.php');
	exit;
}

$_SESSION['idletime']=1800;//after 60 seconds the user gets logged out

if (time()-$_SESSION['timestamp']>$_SESSION['idletime']){
	header('Location: logout.php');
}else{
    $_SESSION['timestamp']=time();
}

?>
<!DOCTYPE html>
	<head>
		<meta charset="utf-8">
		<title>Dashboard - Credit Deals</title>
		<link rel="stylesheet" href="css.css" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
		<link rel="icon" href="icon.ico">
		<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-7923494200662818"
     crossorigin="anonymous"></script>
	</head>
	<body class="loggedin">
		<nav class="navtop">
			<div>
				<h1>Credit Deals</h1>
				<a href="cards.php"><i class="fas fa-credit-card"></i>Edit Cards</a>
				<a href="profile.php"><i class="fas fa-user-circle"></i>Profile</a>
				<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
			</div>
		</nav>
		<div class="content">
			<h2>Dashboard</h2>
				<div>
				<p>Welcome back, <?=$_SESSION['firstname']?>! Here are the deals for today</p>
				<?php
				require __DIR__ . '\getDeals.php';
				
				//Custom Deals
				if(array_key_exists('recordC', $_SESSION)){
					echo "<p><b>Custom Deals</b></p>";
					echo '<div class="row">';
					foreach($_SESSION['recordC'] as $key => $value){
						echo '<div class="col-sm-3">';
						echo '<div class="card text-center text-black bg-light mb-3 border-dark" style="max-width: 18rem;">';
						echo '<div class="card-body">';
						echo '<p class="card-title"><b>'.$value['deal'].'</b></p>';
						echo '<p class="card-text">'.$value['card'].'</p>';
						echo '</div></div></div>';
					}
					echo "</div>";
					
				}
				
				if(array_key_exists('record', $_SESSION)){
				foreach($_SESSION['record'] as $key => $value){
					echo "<p><b>". $value['cardName']. " - ". $value['Description']."</b></p>";
					
					//echo "<table><tr><td><b>Merchant</b></td><td></td><td><b>Offer</b></td><td><b>Expire</b></td></tr>";
					if($value['cardid']==1){
						echo '<div class="row">';				
						$offers = getDeal($value['cardNumber']);
						foreach ($offers as $x => &$offer) {
		
							echo '<div class="col-sm-3">';
							echo '<div class="card text-center text-black bg-light mb-3 border-dark" style="max-width: 18rem;">';
							foreach ($offer["merchantList"] as $y => &$merch){
								//echo "<tr><td>".$merch['merchant']."</td>	";
								foreach ($merch["merchantImages"] as $z => &$image){
									//echo "<td><img src=" .$image['fileLocation']." width='75' height='75'></td>";
									echo '<img class="card-img-top" src='. $image['fileLocation'] .' height="100" alt="Merchant image">';
									break;
								}
								echo '<div class="card-body">';
								//echo '<h5 class="card-title">'.$merch['merchant'].'</h5>';
							}
							echo '<p class="card-title"><b>'.$offer['offerTitle'].'</b></p>';
							echo '<p class="card-text">Ends '.$offer['promotionToDate'].'</p>';
							$url = substr($offer['redemptionUrl'], 11, -5);
							echo '<a href='.$url.'>Redeem</a>';
							//echo "<td>". $offer['offerTitle']."</td>";
							//echo "<td>". $offer['promotionToDate']."</td></tr>";
						echo '</div></div></div>';
						}
						//echo "</table> <br><br>";
						//echo '</div></div>';
						echo '</div>';
					} else{
						echo "<p>Not a Visa Card<p>";
					}
				}
				} else {
					echo "<p><b>No cards added</b><p>";
				}
				?>
			</div>
		</div>
	</body>
</html>
