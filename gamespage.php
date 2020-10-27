<?php
include 'main.php';
	
$gameIndex = 20;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <link href="style.css" rel="stylesheet" type="text/css">
    <title>Document</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js%22%3E"></script>
</head>
<body class="loggedin">
	<aside class="profileAside">
		<div class="sidebar">
			<img class="VoltoxLogo" src="..\uploads\VoltoxLogo.svg" alt="">
			<ul>
				<li><a href="home.php"><i class="fas fa-home"></i>Home</a></li>
				<li><a href="profile.php"><i class="fas fa-user"></i>Profile</a></li>
				<li><a href="allAcounts.php"><i class="fas fa-address-book"></i>Browse users</a></li>
				<li><a href="allAcounts.php"><i class="fas fa-address-book"></i>Browse friends</a></li>
				<li><a href="contact.php"><i class="fas fa-paper-plane"></i>Contact</a></li>
				<li><a href="gamespage.php"><i class="fas fa-gamepad"></i>Games</a></li>
				<li><a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a></li>
			</ul>
		</div>
	</aside>
	<div class="profilePage">
		<nav class="navtop">
			<div>
				<img class="navProfilePic" src="<?=$profile_pic?>"></img>;
				<a href="profile.php"><?= $_SESSION['name'] ?></a>
			</div>
		</nav>
		<div class="content">
			<h2>Games</h2>
			<p>Featured Games</p>
			
		</div>
		
		<div class="slideshow-Image-Border1 borderBottom borderTop">
			<div class="slideshow-container1">
				<div class="slideShow-slide1">
					
				</div>
			</div>
		</div>
		<div class="gamesListContainer">
			<?php
			
			for($i = 1; $i <= 50000; $i++){

				$stmt = $con->prepare('SELECT * FROM gamesInfo WHERE id = ?');
				$stmt->bind_param('s', $i);
				$stmt->execute();
				$result = $stmt->get_result();
				$account = $result->fetch_array(MYSQLI_ASSOC);
				?>
					<h1><?= $account['name'] ?></h1>
				<?php
			}?>
			
		</div>
	</div>
</div>
	<div class="altMenu">
		<div class="asideFriends">
			<h4>Friends</h4>
			<hr>

		</div>
	</div>
	
	<script src="gamePageJS.js"></script>
	<script src="Slideshow.js"></script>
	<script>
	const body = document.querySelector("body");
	body.addEventHandler("scroll", e =>{
		console.log("asd");
	})
	</script>
</body>
</html>