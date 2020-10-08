<?php
include 'main.php';
// No need for the user to see the login form if they're logged-in so redirect them to the home page
if (isset($_SESSION['loggedin'])) {
	// If the user is not logged in redirect to the home page.
	header('Location: home.php');
	exit;
}
// Also check if they are "remembered"
if (isset($_COOKIE['rememberme']) && !empty($_COOKIE['rememberme'])) {
	// If the remember me cookie matches one in the database then we can update the session variables.
	$stmt = $con->prepare('SELECT id, username FROM accounts WHERE rememberme = ?');
	$stmt->bind_param('s', $_COOKIE['rememberme']);
	$stmt->execute();
	$stmt->store_result();
	if ($stmt->num_rows > 0) {
		// Found a match
		$stmt->bind_result($id, $username);
		$stmt->fetch();
		$stmt->close();
		session_regenerate_id();
		$_SESSION['loggedin'] = TRUE;
		$_SESSION['name'] = $username;
		$_SESSION['id'] = $id;
		header('Location: home.php');
		exit;
	}
}
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,minimum-scale=1">
	<title>Login</title>
	<link href="style.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
</head>

<body>
	<svg xmlns="http://www.w3.org/2000/svg" style="display: none">
		<symbol id="checkmark" viewBox="0 0 24 24">
			<path stroke-linecap="round" stroke-miterlimit="10" fill="none" d="M22.9 3.7l-15.2 16.6-6.6-7.1">
			</path>
		</symbol>
	</svg>
	<div class="login">
		<h1>Login</h1>
		<div class="loginWrapper">
			<form action="authenticate.php" method="post">
				<div>
					<input type="text" name="username" placeholder="Username" id="username" required>
				</div>
				<div>
					<input type="password" name="password" placeholder="Password" id="password" required>
				</div>

				<div class="msg"></div>

				<div class="promoted-checkbox">
					<input name="rememberme" id="tmp" type="checkbox" class="promoted-input-checkbox" />
					<label for="tmp">
						<svg>
							<use xlink:href="#checkmark" /></svg>
						remember me
					</label>
				</div>
				<a class="forgotPass" href="forgotpassword.php">Forgot Password?</a>
				<div>
					<input type="submit" value="Login">
				</div>

				<div>
					<p class="regHere">Don't have an account? Register here:</p>
					<div class="links">
						<a href="register.html">Register</a>
					</div>

			</form>

		</div>
	</div>
	<script>
		$(".login form").submit(function(event) {
			event.preventDefault();
			var form = $(this);
			var url = form.attr('action');
			$.ajax({
				type: "POST",
				url: url,
				data: form.serialize(),
				success: function(data) {
					if (data.toLowerCase().includes("success")) {
						window.location.href = "home.php";
					} else {
						$(".msg").text(data);
					}
				}
			});
		});
	</script>
</body>

</html>