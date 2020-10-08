<?php
include 'main.php';
//connects to database
$connect = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}


$output = '';
if (isset($_POST["query"])) {
	$search = mysqli_real_escape_string($connect, $_POST["query"]);
	$query = "
	SELECT * FROM accounts 
	WHERE username LIKE '%" . $search . "%'
	OR id LIKE '%" . $search . "%' 
	";
} else {
	$query = "
	SELECT * FROM accounts ORDER BY id";
}
$result = mysqli_query($connect, $query);
if (mysqli_num_rows($result) > 0) {
	$output .= '<div class="table">
						<tr>
						</tr>';
	while ($row = mysqli_fetch_array($result)) {
		$output .= '
				<a href="user.php?id=' . $row["id"] . '">' . $row["username"] . '</a>' . '<hr>';
	}
	echo $output;
} else {
	echo 'Data Not Found';
}
