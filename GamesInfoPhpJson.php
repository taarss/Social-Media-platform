<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<script>
    <?php
        $DATABASE_HOST = 'localhost';
        $DATABASE_USER = 'christianvillads_techvoltoxdb';
        $DATABASE_PASS = 'Vhh64rpz';
        $DATABASE_NAME = 'christianvillads_techvoltoxdb';
        $con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
        if (mysqli_connect_errno()) {
            exit('Failed to connect to MySQL: ' . mysqli_connect_error());
        }
        $stmt = $con->prepare('INSERT INTO gamesInfo (name, box_art_url) VALUES (?, ?)');
        $stmt->bind_param('ss', "test", "test");
        $stmt->execute()
        ?>
</script>
</body>
</html>