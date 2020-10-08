<?php
include 'main.php';
checkLoggedIn($con);
if (!$_SESSION['name'] == 'admin') {
	header("Location: https://christianvillads.tech/home.php");
	exit();
}
$stmt = $con->prepare('SELECT password, email, bio, profile_pic FROM accounts WHERE id = ?');
$stmt->bind_param('i', $_SESSION['id']);
$stmt->execute();
$stmt->bind_result($password, $email, $bio, $profile_pic);
$stmt->fetch();
$stmt->close();

if (isset($_POST['post_by'], $_POST['post_header'], $_POST['post_text'])) {
	$stmt = $con->prepare('INSERT INTO frontpage_post (post_header, post_text, post_by) VALUES (?, ?, ?)');
	$stmt->bind_param('sss', $_POST['post_header'], $_POST['post_text'], $_POST['post_by']);
	$stmt->execute();
	$stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<link rel="stylesheet" href="style.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
</head>

<body class="loggedin">
	<aside class="profileAside">
		<div class="sidebar">
			<h2>Voltox</h2>
			<ul>
				<li><a href="home.php"><i class="fas fa-home"></i>Home</a></li>
				<li><a href="profile.php"><i class="fas fa-user"></i>Profile</a></li>
				<li><a href="allAcounts.php"><i class="fas fa-address-book"></i>Browse users</a></li>
				<li><a href="contact.php"><i class="fas fa-paper-plane"></i>Contact</a></li>
				<li><a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a></li>
			</ul>
		</div>
		<div class="profileNavBar">
			<div class="profileNavBarContainer">
				<a href="profile.php"><?= $_SESSION['name'] ?></a>
				<?php echo '<img class="asideProfilePic" src="', $profile_pic, '"></img>'; ?>
			</div>
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
			<div class="contactContainer">
				<form action="adminPanel.php" method="post">
					<input type="text" name="post_by" value="<?= $_SESSION['name'] ?>" required>
					<input type="text" name="post_header" placeholder="Headline" required>
					<textarea name="post_text" placeholder="Write your post here:" required></textarea>
					<input type="submit">
				</form>
			</div>
		</div>


</body>

</html>