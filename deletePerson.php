<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remove person</title>
</head>

<?php

include_once "config.php";
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connected successfully";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
if (isset($_GET['id'])) {
    $getId = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM osoby WHERE osoby.id  = $getId");
    $stmt->execute();
}

?>

<body>

    <h1>Vymazana osoba</h1>
</body>

</html>