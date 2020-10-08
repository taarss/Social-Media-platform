<?php
include 'main.php';
checkLoggedIn($con);

//connects to database
$mysqli = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
//determin the number of results per page and number og pages necessary for displaying all items in database
$results_per_page = 8;
$sql = "SELECT * FROM accounts";
$result4 = mysqli_query($mysqli, $sql);
//counts number of results
$number_of_results = mysqli_num_rows($result4);

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

$stmt = $con->prepare('SELECT profile_pic FROM accounts WHERE id = ?');
$stmt->bind_param('i', $_SESSION['id']);
$stmt->execute();
$stmt->bind_result($profile_pic);
$stmt->fetch();
$stmt->close();
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
            <div class="navContent">
                <?php echo '<img class="navProfilePic" src="', $profile_pic, '"></img>'; ?>
                <a href="profile.php"><?= $_SESSION['name'] ?></a>
            </div>
        </nav>
        <div class="content">
            <div class="profileHeader">
                <div>
                    <h2>Browse fellow users</h2>
                </div>
                <a id="searchLink" href="searchUser.php">Search</a>
            </div>
            <div class="accountsContainer">
                <?php

                $query = "SELECT id, username, email, date_created, profile_pic FROM accounts LIMIT " . $this_page_first_result . ',' . $results_per_page;
                $stmt = $mysqli->prepare($query);
                $stmt->execute();
                $result = $stmt->get_result();
                $users = $result->fetch_all(MYSQLI_ASSOC);

                //A loop that displays every user
                foreach ($users as $row) { ?>
                    <a href="user.php?id=<?= $row['id'] ?>">
                        <div class="cell">
                            <img class="allProfilePic" src="<?= $row['profile_pic'] ?>"></img>
                            <p><?= $row['username'] ?></p>
                        </div>
                    </a>
                    <hr>
                <?php } ?>
            </div>
            <div id="pagenation">
                <?php
                //displays links to next pages
                for ($page = 1; $page <= $number_of_pages; $page++) {
                    echo '<a href="allAcounts.php?page=' . $page . '">' . $page . '</a>';
                }

                ?>
            </div>
        </div>
</body>

</html>