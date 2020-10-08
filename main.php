<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'christianvillads_techvoltoxdb';
$DATABASE_PASS = 'Vhh64rpz';
$DATABASE_NAME = 'christianvillads_techvoltoxdb';
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
// The below function will check if the user is logged-in and also check the remember me cookie
function checkLoggedIn($con)
{
    // You can add the remember me part below in all your files that require it (home, profile, etc).
    if (isset($_COOKIE['rememberme']) && !empty($_COOKIE['rememberme']) && !isset($_SESSION['loggedin'])) {
        // If the remember me cookie matches one in the database then we can update the session variables.
        $stmt = $con->prepare('SELECT id, username FROM accounts WHERE rememberme = ?');
        $stmt->bind_param('s', $_COOKIE['rememberme']);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
        } else {
            // If the user is not logged in redirect to the login page.
            header('Location: index.php');
            exit;
        }
    } else if (!isset($_SESSION['loggedin'])) {
        // If the user is not logged in redirect to the login page.
        header('Location: index.php');
        exit;
    }
}
if (isset($_SESSION['loggedin'])) {

    $stmt = $con->prepare('SELECT user_id, last_heartbeat FROM online_users');
    $stmt->execute();
    $result = $stmt->get_result();
    $users = $result->fetch_all(MYSQLI_ASSOC);
    if (isUserOnline($users)) {
        foreach ($users as $row) {
            $currentNewTime = new DateTime('now');
            $result = $currentNewTime->format('Y-m-d H:i:s');
            if ($row['user_id'] == $_SESSION['id']) {

                $stmt = $con->prepare('UPDATE online_users SET last_heartbeat = ? WHERE user_id = ?');
                $stmt->bind_param('ss', $result, $_SESSION['id']);
                $stmt->execute();
                $stmt->close();
            }
        }
        foreach ($users as $row) {
            /*
        $myDateTime = DateTime::createFromFormat('Y-m-d H:i:s', $result);
        $myDateTime2 = DateTime::createFromFormat('Y-m-d H:i:s', $dtNow);
        $dtToCompare = $myDateTime;
        $diff = $dtNow - $dtToCompare;*/
            $newTime = $row['last_heartbeat'];
            $dtNow = new DateTime();
            $result = $dtNow->format('Y-m-d H:i:s');
            $timeFirst  = strtotime($result);
            $timeSecond = strtotime($newTime);
            $diff = ($timeFirst - $timeSecond);
            if ($diff > 300) {
                $stmt = $con->prepare('DELETE FROM online_users WHERE user_id = ?');
                $stmt->bind_param('i', $row['user_id']);
                $stmt->execute();
            }
        }
    } else {
        $currentNewTime = new DateTime('now');
        $result = $currentNewTime->format('Y-m-d H:i:s');
        $stmt = $con->prepare('INSERT INTO online_users (user_id, last_heartbeat) VALUES (?, ?)');
        $stmt->bind_param('ss', $_SESSION['id'], $result);
        $stmt->execute();
        $stmt->close();
    }
}


function isUserOnline($users)
{
    foreach ($users as $row) {
        if ($row['user_id'] == $_SESSION['id']) {
            return true;
        }
    }
    return false;
}
