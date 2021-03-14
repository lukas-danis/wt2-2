<?php
include_once "classes/Person.php";
include_once "config.php";
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connected successfully";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

if (isset($_POST['placingOH']) && isset($_POST['disciplineOH'])) {
    if (isset($_POST['placingOH']) && $_POST['disciplineOH']) {
        $currentOhId = $_POST['ohId'];
        if ($_POST['ohId'] == "ine") {
            $stmt = $conn->prepare("Insert into oh (type, year, ord, city, country) values (:type, :year, :ord, :city, :country)");
            $stmt->bindParam(":type", $_POST['typeOH'], PDO::PARAM_STR);
            $stmt->bindParam(":year", $_POST['yearOH'], PDO::PARAM_STR);
            $stmt->bindParam(":ord", $_POST['ordOH'], PDO::PARAM_STR);
            $stmt->bindParam(":city", $_POST['cityOH'], PDO::PARAM_STR);
            $stmt->bindParam(":country", $_POST['countryOH'], PDO::PARAM_STR);
            $stmt->execute();
            $currentOhId = $conn->lastInsertId();
        }
        $stmt = $conn->prepare("Insert into umiestnenia (person_id, oh_id, placing, discipline) values (:person_id, :oh_id, :placing, :discipline)");
        $stmt->bindParam(":person_id", $_POST['personId'], PDO::PARAM_INT);
        $stmt->bindParam(":oh_id", $currentOhId, PDO::PARAM_INT);
        $stmt->bindParam(":placing", $_POST['placingOH'], PDO::PARAM_INT);
        $stmt->bindParam(":discipline", $_POST['disciplineOH'], PDO::PARAM_STR);
        $stmt->execute();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pridanie vysledku</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="addScript.js"></script>
</head>

<body>
    <h1>Add result</h1>

    <form method="post">
        <label for="personId">Vyber sportovca</label>
        <select name="personId" id="personId">
            <?php
            $stmt = $conn->prepare("select * from osoby");
            $stmt->execute();
            $people = $stmt->fetchAll();
            foreach ($people as $person) {
                echo "<option value='" . $person["id"] . "'>" . $person["name"] . " " . $person["surname"] . "</option>";
            }
            ?>
        </select>
        <br>
        <label for="ohId">Vyber olympiadu</label>
        <select name="ohId" id="ohId" onchange="makeItVisible()">
            <option value="ine">ine</option>
            <?php
            $stmt = $conn->prepare("select * from oh");
            $stmt->execute();
            $alloh = $stmt->fetchAll();
            foreach ($alloh as $oneoh) {
                echo "<option value='" . $oneoh["id"] . "'>" . $oneoh["city"] . ", " . $oneoh["country"] . ", " . $oneoh["type"] . ", " . $oneoh["year"] . "</option>";
            }
            ?>
        </select>
        <br>
        <div id="ohInputs">
            <label for="countryOH">Krajina konania</label>
            <input type="text" name="countryOH" id="countryOH" value="<?php echo null ?>">
            <br>
            <label for="cityOH">Miesto konania</label>
            <input type="text" name="cityOH" id="cityOH" value="<?php echo null ?>">
            <br>
            <label for="yearOH">Rok konania</label>
            <input type="text" name="yearOH" id="yearOH" value="<?php echo null ?>">
            <br>
            <label for="typeOH">Type OH</label>
            <input type="text" name="typeOH" id="typeOH" value="<?php echo null ?>">
            <br>
            <label for="ordOH">Poradie OH</label>
            <input type="number" name="ordOH" id="ordOH" value="<?php echo null ?>">
            <br>
        </div>


        <label for="placingOH">Umiestnenie</label>
        <input type="number" name="placingOH" id="placingOH" value="<?php echo null ?>">
        <br>
        <label for="disciplineOH">Disciplína</label>
        <input type="text" name="disciplineOH" id="disciplineOH" value="<?php echo null ?>">
        <br>


        <input type="submit">
    </form>
</body>

</html>