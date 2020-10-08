<?php
include 'main.php';
checkLoggedIn($con);
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'christianvillads_techgaimdb';
$DATABASE_PASS = 'Aspit123';
$DATABASE_NAME = 'christianvillads_techgaimdb';
$mysqli = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

$userid = (int)$_GET['id'];
$query = "SELECT id, username, email, date_created, bio, profile_pic, isAdmin FROM accounts WHERE id = ? ";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $userid);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
function addFriend()
{
	global $con;
	$stmt = $con->prepare('INSERT INTO friends (friendIs, friendOf) VALUES (?, ?)');
	global $user;
	global $_SESSION;

	$stmt->bind_param('ii', $user['id'], $_SESSION['id']);
	$stmt->execute();
	$stmt->close();
}


function checkIfFriends()
{
	$friend = null;
	global $user;
	global $_SESSION;
	global $con;
	$stmt = $con->prepare('SELECT friendIs FROM friends WHERE friendOf = ? AND friendIs = ?');
	$stmt->bind_param('ii', $_SESSION['id'], $user['id']);
	$stmt->execute();
	$stmt->bind_result($friend);
	$stmt->fetch();
	$stmt->close();
	if ($friend != null) {
		echo "Status: Friends";
		return true;
	} else {
		return false;
	}
}

if (array_key_exists('test', $_POST)) {
	addFriend();
}

//var_dump($user['username']);
//var_dump($user['email']);
//var_dump($user['date_created']);

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
	<?php
	$stmt = $con->prepare('SELECT profile_pic FROM accounts WHERE id = ?');
	$stmt->bind_param('i', $_SESSION['id']);
	$stmt->execute();
	$stmt->bind_result($profile_pic);
	$stmt->fetch();
	$stmt->close();
	?>
	<div class="profilePage">
		<nav id="navTop" class="navtop">
			<div class="navContent">
				<img class="navProfilePic" src="<?= $profile_pic ?>"></img>
				<a href="profile.php"><?= $_SESSION['name'] ?></a>
			</div>
		</nav>
		<div class="content">



			<div class="profileHeader">
				<div>
					<h2><?= $user['username'] ?>'s Profile</h2>
					<p><?= $user['username'] ?>'s personal profile</p>

				</div>
			</div>





			<div class="nameContainer">
				<img class="profilePic" src="<?= $user['profile_pic'] ?>"></img>
				<div>
					<p><?= $user['username'] ?> 
						<?php
						if ($user['isAdmin'] == true) { ?>
							<i class="fas fa-user-shield"></i>
						<?php
						}

						?>
					</p>
					<p>Account created: <?= $user['date_created'] ?></p>
					<p><?php echo getCountry(); ?></p>
					<?php
					if (checkIfFriends() == false) { ?>
						<form method="post">
							<input type="submit" name="test" id="test" value="Add friend" /><br />
						</form>
					<?php
					}

					?>


				</div>
			</div>
			<div class="bioContainer">
				<p><?= $user['username'] ?>'s Bio</p>
				<div class="userBio">
					<div>
						<p><?= $user['bio'] ?></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>

</html>