<?php
include 'main.php';
checkLoggedIn($con);
$stmt = $con->prepare('SELECT id, password, email, bio, profile_pic, isAdmin FROM accounts WHERE id = ?');
// In this case we can use the account ID to get the account info.
$stmt->bind_param('i', $_SESSION['id']);
$stmt->execute();
$stmt->bind_result($id, $password, $email, $bio, $profile_pic, $isAdmin);
$stmt->fetch();
$stmt->close();

$stmt = $con->prepare('SELECT user_id FROM online_users');
$stmt->execute();
$stmt->bind_result($onlineUserIds);
$stmt->fetch();
$stmt->close();

$stmt = $con->prepare('SELECT friendIs FROM friends WHERE friendOf = ?');
$stmt->bind_param('i', $_SESSION['id']);
$stmt->execute();
$stmt->bind_result($friendIs);
$stmt->fetch();
$stmt->close();
echo var_dump($friendIs);
if (is_array($friendIs)) {
	$onlineFriends = array_intersect($friendIs, $onlineFriends);
}

$mysqli = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
//detrmin the number of results per page and number og pages necessary for displaying all items in the database
$results_per_page = 100;
$sql = "SELECT * FROM frontpage_post";
$result4 = mysqli_query($mysqli, $sql);
//counts number of results
//$number_of_results = mysqli_num_rows($result4);

//rounds number of result up
$number_of_pages = ceil($number_of_results / $results_per_page);

//if not on any page (in url) then go to page 1
if (!isset($_GET['page'])) {
	$page = 1;
} else {
	$page = $_GET['page'];
}

//calculate numver of results for page for query
$this_page_first_result = ($page - 1) * $results_per_page;





?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,minimum-scale=1">
	<title>Home Page</title>
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
			<?php
			$query = "SELECT post_id, post_header, post_text, post_by, post_time FROM frontpage_post LIMIT " . $this_page_first_result . ',' . $results_per_page;
			$stmt = $mysqli->prepare($query);
			$stmt->execute();
			$result = $stmt->get_result();
			$users = $result->fetch_all(MYSQLI_ASSOC);

			//A loop that displays every user
			foreach ($users as $row) { ?>
				<div class="tableContainer" id="postContainer">
					<h3><?= $row['post_header'] ?></h3>
					<div class="frontPagePostInfo">
						<p class="frontPagePostByline">Posted by <?= $row['post_by'] ?>
							<?php
							if ($isAdmin == true) { ?>
								<i id="adminIcon" class="fas fa-user-shield"></i>
							<?php
							}

							?>

						</p>
						<p class="frontPagePostTime"><?= $row['post_time'] ?></p>
					</div>
					<p class="frontPagePostContent"><?= $row['post_text'] ?></p>
				</div>
			<?php } ?>
			<div id="pagenation">
				<?php
				//displays links to next pages
				for ($page = 1; $page <= $number_of_pages; $page++) {
					echo '<a href="home.php?page=' . $page . '">' . $page . '</a>';
				}

				?>
			</div>
		</div>
	</div>
	<div class="altMenu">
		<div class="asideFriends">
			<h4>Friends</h4>
			<hr>
			<?php foreach ($onlineFriends as $row) {

				echo '<a href="user.php?id=' . $page . '">' . $page . '</a>';
			} ?>

		</div>
	</div>


</body>
<script>
	fetch('http://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js').catch(() => {
		let adp_underlay = document.createElement('div');
		adp_underlay.className = 'adp-underlay';
		document.body.appendChild(adp_underlay);
		let adp = document.createElement('div');
		adp.className = 'adp';
		adp.innerHTML = `
		<h3>Ad Blocker Detected!</h3>
		<p>We use advertisements to keep our website online, could you please whitelist our website, thanks!</p>
		<a href="#">Okay</a>
	`;
		document.body.appendChild(adp);
		adp.querySelector('a').onclick = e => {
			e.preventDefault();
			document.body.removeChild(adp_underlay);
			document.body.removeChild(adp);
		};
	});

	windows.setInterval(keepAlive, 20000);
</script>

</html>