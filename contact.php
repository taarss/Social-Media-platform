<?php
include 'main.php';
checkLoggedIn($con);
// We don't have the password or email info stored in sessions so instead we can get the results from the database.
$stmt = $con->prepare('SELECT password, email FROM accounts WHERE id = ?');
// In this case we can use the account ID to get the account info.
$stmt->bind_param('i', $_SESSION['id']);
$stmt->execute();
$stmt->bind_result($password, $email);
$stmt->fetch();
$stmt->close();
$response = '';
if (isset($_POST['email'], $_POST['subject'], $_POST['name'], $_POST['msg'])) {
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $response = 'Email is not valid';
    } elseif (empty($_POST['email']) || empty($_POST['subject']) || empty($_POST['name']) || empty($_POST['msg'])) {
        $response = 'please make sure that all fields are filled in';
    } else {
        $to = 'contact@christianvillads.tech';
        $from = $_POST['email'];
        $subject = $_POST['subject'];
        $message = $_POST['msg'];
        $headers = 'From: ' . $_POST['email'] . "\r\n" . 'Reply-To: ' . $_POST['email'] . "\r\n" . 'X-Mailer: PHP/' . phpversion();
        mail($to, $subject, $message, $headers);
        $response = 'Message sent!';
    }
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
    <?php
    $stmt = $con->prepare('SELECT profile_pic FROM accounts WHERE id = ?');
    $stmt->bind_param('i', $_SESSION['id']);
    $stmt->execute();
    $stmt->bind_result($profile_pic);
    $stmt->fetch();
    $stmt->close();
    ?>
    <div class="profilePage">
        <nav class="navtop">
            <div>
                <img class="navProfilePic" src="<?= $profile_pic ?>"></img>

                <a href="profile.php"><?= $_SESSION['name'] ?></a>
            </div>
        </nav>
        <div class="content">



            <div class="profileHeader">
                <div>
                    <h2>Contact us</h2>
                    <p>Contact form:</p>
                </div>
            </div>
            <div class="contactContainer">
                <form method="post" action="contact.php">
                    <input type="email" name="email" placeholder="Your Email Address" required>
                    <input type="text" name="name" placeholder="Your Name" required>
                    <input type="text" name="subject" placeholder="Subject" required>
                    <textarea name="msg" placeholder="What would you like to contact us about?" required></textarea>
                    <input type="submit">
                </form>
                <?php if ($response) : ?>
                    <p><?php echo $response; ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>

</body>

</html>