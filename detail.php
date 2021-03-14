<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail</title>
</head>

<?php
include_once "config.php";
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //   echo "Connected successfully";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

<?php
$getId = "0";
if ($_GET['id']) {
    $getId = $_GET['id'];
}

?>

<body>
    <h1>Detail sportovca</h1>
    <?php

    $stmt = $conn->prepare("SELECT * FROM osoby WHERE osoby.id  = $getId");
    $stmt->execute();
    $row = $stmt->fetch();
    echo "<h2>" . $row["name"] . " " . $row["surname"] . "</h2>";
    echo "<p>" . $row["birth_day"] . "</p>";
    ?>

    <div class="wd70">
        <table id='myTable' class='display' style='border: solid 1px black;'>
            <thead>
                <tr>
                    <th>Krajina a Mesto</th>
                    <th>Rok</th>
                    <th>Typ</th>
                    <th>Disciplína</th>
                    <th>Umiestnenie</th>
                </tr>
            </thead>
            <?php
            $stmt = $conn->prepare("SELECT CONCAT(oh.city, ', ', oh.country) as city, oh.year, oh.type, umiestnenia.discipline, umiestnenia.placing
                FROM oh, osoby, umiestnenia WHERE osoby.id = $getId and umiestnenia.person_id = osoby.id and umiestnenia.oh_id = oh.id");
            $stmt->execute();
            $rows = $stmt->fetchAll();
            foreach ($rows as $row) {
                echo "<tr>";
                echo "<td style='width:150px;border:1px solid black;'>" . $row["city"] . "</td>";
                echo "<td style='width:150px;border:1px solid black;'>" . $row["year"] . "</td>";
                echo "<td style='width:150px;border:1px solid black;'>" . $row["type"] . "</td>";
                echo "<td style='width:150px;border:1px solid black;'>" . $row["discipline"] . "</td>";
                echo "<td style='width:150px;border:1px solid black;'>" . $row["placing"] . "</td>";
                echo "</tr>" . "\n";
            }
            ?>
        </table>
    </div>
    <a href="index.php">Naspak</a>
</body>

</html>