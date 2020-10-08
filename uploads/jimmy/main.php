<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'christianvillads_techgaimdb';
$DATABASE_PASS = 'Aspit123';
$DATABASE_NAME = 'christianvillads_techgaimdb';
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
        $differenceInSeconds = ($timeFirst - $timeSecond);
        
        if ($diff > 5) {
            echo "Deleted user from online table";
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

function isUserOnline($users)
{
    foreach ($users as $row) {
        if ($row['user_id'] == $_SESSION['id']) {
            return true;
        }
    }
    return false;
}


function getCountry($ip = NULL, $purpose = "location", $deep_detect = TRUE)
{
    $DATABASE_HOST = 'christianvillads.tech.mysql';
    $DATABASE_USER = 'christianvillads_techgaimdb';
    $DATABASE_PASS = 'Aspit123';
    $DATABASE_NAME = 'christianvillads_techgaimdb';
    $con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
    if (mysqli_connect_errno()) {
        exit('Failed to connect to MySQL: ' . mysqli_connect_error());
    }
    $output = NULL;
    if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
        $ip = $_SERVER["REMOTE_ADDR"];
        if ($deep_detect) {
            if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
    }
    $purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
    $support    = array("country", "countrycode", "state", "region", "city", "location", "address");
    $continents = array(
        "AF" => "Africa",
        "AN" => "Antarctica",
        "AS" => "Asia",
        "EU" => "Europe",
        "OC" => "Australia (Oceania)",
        "NA" => "North America",
        "SA" => "South America"
    );
    if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
        $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
        if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
            switch ($purpose) {
                case "location":
                    $output = array(
                        "city"           => @$ipdat->geoplugin_city,
                        "state"          => @$ipdat->geoplugin_regionName,
                        "country"        => @$ipdat->geoplugin_countryName,
                        "country_code"   => @$ipdat->geoplugin_countryCode,
                        "continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                        "continent_code" => @$ipdat->geoplugin_continentCode
                    );
                    break;
                case "address":
                    $address = array($ipdat->geoplugin_countryName);
                    if (@strlen($ipdat->geoplugin_regionName) >= 1)
                        $address[] = $ipdat->geoplugin_regionName;
                    if (@strlen($ipdat->geoplugin_city) >= 1)
                        $address[] = $ipdat->geoplugin_city;
                    $output = implode(", ", array_reverse($address));
                    break;
                case "city":
                    $output = @$ipdat->geoplugin_city;
                    break;
                case "state":
                    $output = @$ipdat->geoplugin_regionName;
                    break;
                case "region":
                    $output = @$ipdat->geoplugin_regionName;
                    break;
                case "country":
                    $output = @$ipdat->geoplugin_countryName;
                    break;
                case "countrycode":
                    $output = @$ipdat->geoplugin_countryCode;
                    break;
            }
        }
    }

    $output = $output['country'];

    $stmt = $con->prepare('UPDATE accounts SET country = ? WHERE id = ?');
    $stmt->bind_param('si', $output, $_SESSION['id']);
    $stmt->execute();
    $stmt->close();
    // Fetch the updated account details
    $stmt = $con->prepare('SELECT country FROM accounts WHERE id = ?');
    $stmt->bind_param('i', $_SESSION['id']);
    $stmt->execute();
    $stmt->bind_result($output);
    $stmt->fetch();
    $stmt->close();
    return $output;
}
