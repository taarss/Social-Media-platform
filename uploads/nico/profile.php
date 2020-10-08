<?php
include 'main.php';
checkLoggedIn($con);


function change_profile_image($user_id, $file_temp, $file_extn, $con)
{
	$file_path = 'uploads/' . substr(md5(time()), 0, 10) . '.' . $file_extn;
	move_uploaded_file($file_temp, $file_path);
	$stmt = $con->prepare('UPDATE accounts SET profile_pic = ? WHERE id = ?');
	$stmt->bind_param('si', $file_path, $user_id);
	$stmt->execute();
	$stmt->close();
}

// We don't have the password or email info stored in sessions so instead we can get the results from the database.
$stmt = $con->prepare('SELECT password, email, bio, profile_pic, isAdmin FROM accounts WHERE id = ?');
// In this case we can use the account ID to get the account info.
$stmt->bind_param('i', $_SESSION['id']);
$stmt->execute();
$stmt->bind_result($password, $email, $bio, $profile_pic, $isAdmin);
$stmt->fetch();
$stmt->close();
// Handle edit profile post data
if (isset($_POST['password'], $_POST['email'], $_POST['userBio'])) {
	$stmt = $con->prepare('UPDATE accounts SET password = ?, email = ?, bio = ? WHERE id = ?');
	// We do not want to expose passwords in our database, so hash the password and use password_verify when a user logs in.
	$password = $password != $_POST['password'] ? password_hash($_POST['password'], PASSWORD_DEFAULT) : $password;
	$stmt->bind_param('sssi', $password, $_POST['email'], $_POST['userBio'], $_SESSION['id']);
	$stmt->execute();
	$stmt->close();
	// Fetch the updated account details
	$stmt = $con->prepare('SELECT password, email, bio, profile_pic FROM accounts WHERE id = ?');
	$stmt->bind_param('i', $_SESSION['id']);
	$stmt->execute();
	$stmt->bind_result($password, $email, $bio, $profile_pic);
	$stmt->fetch();
	$stmt->close();
}



?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,minimum-scale=1">
	<title>Profile Page</title>
	<link href="style.css" rel="stylesheet" type="text/css">
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
	<?php if (!isset($_GET['action'])) : ?>
		<div class="profilePage">
			<nav class="navtop">
				<div>
					<?php echo '<img class="navProfilePic" src="', $profile_pic, '"></img>'; ?>
					<a href="profile.php"><?= $_SESSION['name'] ?></a>
				</div>
			</nav>
			<div class="content">



				<div class="profileHeader">
					<div>
						<h2><?= $_SESSION['name'] ?>'s account</h2>
						<p>My details:</p>
					</div>
					<div>

						<a href="profile.php?action=edit">Edit Details</a>
					</div>
					<?php
					if (isset($_FILES['upload']) === true) {
						$allowed = array('jpg', 'jpeg', 'gif', 'png', strtolower(end(explode('.', $profile_pic))));
						$file_name = $_FILES['upload']['name'];
						$file_extn = strtolower(end(explode('.', $file_name)));
						$file_temp = $_FILES['upload']['tmp_name'];
						if (in_array($file_extn, $allowed) === true) {
							echo 'sssfsfsfsfsffsfs';
							change_profile_image($_SESSION['id'], $file_temp, $file_extn, $con);
						} elseif (in_array($file_extn, $allowed) === false) {
							echo 'Incorrect file type ';
							echo implode(',', $allowed);
						} else {
							echo 'fejl';
						}
					}
					?>
				</div>





				<div class="nameContainer">
					<?php echo '<img class="profilePic" src="', $profile_pic, '"></img>'; ?>
					<div>
						<p><?= $_SESSION['name'] ?>
							<?php
							if ($isAdmin == true) { ?>
								<i class="fas fa-user-shield"></i>
							<?php
							}

							?>										
						</p>
						<p><?= $email ?></p>
						<p><?php echo getCountry(); ?></p>
					</div>
				</div>
				<div>




				</div </div> </div> </div> </div> <?php elseif ($_GET['action'] == 'edit') : ?> <div class="profilePage">
				<nav class="navtop">
					<div>
						<?php echo '<img class="navProfilePic" src="', $profile_pic, '"></img>'; ?>
						<a href="profile.php"><?= $_SESSION['name'] ?></a>
					</div>
				</nav>

				<div class="content">
					<div class="goBack">
						<a href="profile.php"><i class="fas fa-greater-than fa-2x"></i></a>
					</div>
					<div class="editWrapper">
						<div>
							<form action="profile.php" method="post" enctype="multipart/form-data">
								<label for="profile picture">Profile</label>
								<input type="file" name="upload">
								<label for="username">Username</label>
								<input type="text" value="<?= $_SESSION['name'] ?>" name="username" id="username">
								<label for="password">Password</label>
								<input type="password" value="" name="password" id="password">
								<label for="email">Email</label>
								<input type="email" value="<?= $email ?>" name="email" id="email">
								<br>
								<textarea id="editBioText" name="userBio" placeholder="Customize your very own bio"><?= $bio ?></textarea>

								<input type="submit" value="Save">
							</form>
						</div>
					</div>
				</div>
			<?php endif; ?>
</body>

</html>