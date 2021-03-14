<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.css">
  <link rel="stylesheet" type="text/css" href="mystyle.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.js"></script>
  <script src="script.js"></script>
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
?>

<body>
  <div class="wd70">
    <table id='myTable' class='display' style='border: solid 1px black;'>
      <thead>
        <tr>
          <th>Meno</th>
          <th>Mesto</th>
          <th>Rok</th>
          <th>Typ</th>
          <th>Disciplína</th>
        </tr>
      </thead>
      <?php
        $stmt = $conn->prepare("SELECT osoby.id, CONCAT(osoby.surname, ' ',osoby.name) as meno, CONCAT(oh.city, ', ', oh.country) as city, oh.year, oh.type, umiestnenia.discipline
          FROM oh, osoby, umiestnenia WHERE umiestnenia.person_id = osoby.id and umiestnenia.oh_id = oh.id and umiestnenia.placing = '1'");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        foreach ($rows as $row) {
          echo "<tr>";
          echo "<td style='width:150px;border:1px solid black;'>" . "<a href='detail.php?id=" . $row["id"] . "'>" . $row["meno"] . "</a></td>";
          echo "<td style='width:150px;border:1px solid black;'>" . $row["city"] . "</td>";
          echo "<td style='width:150px;border:1px solid black;'>" . $row["year"] . "</td>";
          echo "<td style='width:150px;border:1px solid black;'>" . $row["type"] . "</td>";
          echo "<td style='width:150px;border:1px solid black;'>" . $row["discipline"] . "</td>";
          echo "</tr>" . "\n";
        }
      ?>
    </table>
  </div>

  <div class="wd70">
    <table id='' class='display' style='border: solid 1px black;'>
      <thead>
        <tr>
          <th>Meno</th>
          <th>Počet zlatých</th>
          <th></th>
        </tr>
      </thead>
      <?php
        $stmt = $conn->prepare("SELECT osoby.id, CONCAT(osoby.surname, ' ',osoby.name) as meno, COUNT(*) as pocet
            FROM osoby, umiestnenia WHERE umiestnenia.person_id = osoby.id and umiestnenia.placing = '1'  
            GROUP BY osoby.id ORDER BY pocet DESC");
          
        $stmt->execute();
        $rows = $stmt->fetchAll();
        foreach ($rows as $row) {
          echo "<tr>";
          echo "<td style='width:150px;border:1px solid black;'>" . "<a href='detail.php?id=" . $row["id"] . "'>" . $row["meno"] . "</a></td>";
          echo "<td style='width:150px;border:1px solid black;'>" . $row["pocet"] . "</td>";
          echo "<td style='width:150px;border:1px solid black;'><a href='edit.php?id=" . $row["id"] . "'>EDIT</a>  <a href='deletePerson.php?id=" . $row["id"] . "'>REMOVE</a></td>";
          echo "</tr>" . "\n";
        }
      ?>
    </table>
  </div>
  <a href="edit.php">Add person</a>
  <a href="addResult.php">Add result</a>
</body>

</html>