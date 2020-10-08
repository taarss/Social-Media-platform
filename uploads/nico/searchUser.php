<?php
include 'main.php';
checkLoggedIn($con);

//connects to database
$mysqli = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

$stmt = $con->prepare('SELECT profile_pic FROM accounts WHERE id = ?');
$stmt->bind_param('i', $_SESSION['id']);
$stmt->execute();
$stmt->bind_result($profile_pic);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Search for user</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<link rel="stylesheet" href="style.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
</head>

<body class="loggedin">
	<aside class="profileAside" id="profileSearch">
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
	<div class="profilePage" id="profileSearch">
		<nav class="navtop">
			<div class="navContent">
				<?php echo '<img class="navProfilePic" src="', $profile_pic, '"></img>'; ?>
				<a href="profile.php"><?= $_SESSION['name'] ?></a>
			</div>
		</nav>
		<div class="content">
			<div class="goBack">
				<a href="profile.php"><i class="fas fa-greater-than fa-2x"></i></a>
			</div>
			<div class="profileHeader" class="profileHeaderSearch">
				<div class="input-group">
					<input type="text" name="search_text" id="search_text" placeholder="Search by name or id" class="form-control" />
				</div>
			</div>


			<div class="accountsContainer">
				<div id="result">
					<p></p>
				</div>

			</div>

		</div>
</body>

</html>


<script>
	$(document).ready(function() {
		load_data();

		function load_data(query) {
			$.ajax({
				url: "fetch.php",
				method: "post",
				data: {
					query: query
				},
				success: function(data) {
					$('#result').html(data);
				}
			});
		}

		$('#search_text').keyup(function() {
			var search = $(this).val();
			if (search != '') {
				load_data(search);
			} else {
				load_data();
			}
		});
	});
</script>