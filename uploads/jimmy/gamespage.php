<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <link href="style.css" rel="stylesheet" type="text/css">
    <title>Document</title>
</head>
<body class="loggedin">
	<aside class="profileAside">
		<div class="sidebar">
			<h2>Voltox</h2>
			<ul>
				<li><a href="home.php"><i class="fas fa-home"></i>Home</a></li>
				<li><a href="profile.php"><i class="fas fa-user"></i>Profile</a></li>
				<li><a href="allAcounts.php"><i class="fas fa-address-book"></i>Browse users</a></li>
				<li><a href="allAcounts.php"><i class="fas fa-address-book"></i>Browse friends</a></li>
				<li><a href="contact.php"><i class="fas fa-paper-plane"></i>Contact</a></li>
				<li><a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a></li>
			</ul>
		</div>

	</aside>

	<div class="profilePage">
		<nav class="navtop">
			<div>
				<?php echo '<img class="navProfilePic" src="', $profile_pic, '"></img>'; ?>
				<a href="profile.php"><?= $_SESSION['name'] ?></a>
			</div>
		</nav>
		<div class="content">
			<h2>Home Page</h2>
			<p>Welcome back, <?= $_SESSION['name'] ?>!</p>
			
			</div>
		</div>
	</div>
	<div class="altMenu">
		<div class="asideFriends">
			<h4>Friends</h4>
			<hr>

		</div>
	</div>


</body>
</html>