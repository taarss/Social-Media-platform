<?php
include 'main.php';
// Output message
$msg = '';
// Now we check if the data from the login form was submitted, isset() will check if the data exists.
if (isset($_GET['email'], $_GET['code']) && !empty($_GET['code'])) {
    // Prepare our SQL, preparing the SQL statement will prevent SQL injection.
    $stmt = $con->prepare('SELECT * FROM accounts WHERE email = ? AND reset = ?');
    $stmt->bind_param('ss', $_GET['email'], $_GET['code']);
    $stmt->execute();
    $result = $stmt->get_result();
    $account = $result->fetch_array(MYSQLI_ASSOC);
    $stmt->close();
    // If the account exists with the email and code
    if ($account) {
        if (isset($_POST['npassword'], $_POST['cpassword'])) {
            if (strlen($_POST['npassword']) > 20 || strlen($_POST['npassword']) < 5) {
                $msg = 'Password must be between 5 and 20 characters long!';
            } else if ($_POST['npassword'] != $_POST['cpassword']) {
                $msg = 'Passwords must match!';
            } else {
                $stmt = $con->prepare('UPDATE accounts SET password = ?, reset = "" WHERE email = ?');
                $password = password_hash($_POST['npassword'], PASSWORD_DEFAULT);
                $stmt->bind_param('ss', $password, $_GET['email']);
                $stmt->execute();
                $stmt->close();
                $msg = 'Password has been reset! You can now <a href="index.php">login</a>!';
            }
        }
    } else {
        exit('Incorrect email and/or code!');
    }
} else {
    exit('Please provide the email and code!');
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,minimum-scale=1">
    <title>Reset Password</title>
    <link href="style.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
</head>

<body>
    <div class="login">
        <h1>Reset Password</h1>
        <form class="forgotPassword" action="resetpassword.php?email=<?= $_GET['email'] ?>&code=<?= $_GET['code'] ?>" method="post">
            <input type="password" name="npassword" placeholder="New Password" id="npassword" required>
            <input type="password" name="cpassword" placeholder="Confirm Password" id="cpassword" required>
            <div class="msg"><?= $msg ?></div>
            <input type="submit" value="Submit">
        </form>
    </div>
</body>

</html>