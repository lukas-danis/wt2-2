<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit person</title>
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


if (isset($_POST['name'])) {
    $bolSomTu = false;
    if (isset($_POST['id']) && $_POST['id']) {
        $stmt = $conn->prepare("Update osoby set name=:name, surname=:surname, birth_day=:birth_day, birth_place=:birth_place, birth_country=:birth_country, death_day=:death_day, death_place=:death_place, death_country=:death_country where id = :personId");
        $stmt->bindParam(":personId", $_POST['id'], PDO::PARAM_INT);
    } else {
        $stmt = $conn->prepare("select * from osoby");
        $stmt->execute();
        $people = $stmt->fetchAll();
        foreach ($people as $person) {
           if($person["name"] == $_POST['name'] && $person["surname"] ==$_POST['surname']){
            $bolSomTu = true;
            break;
           }
        }
        $stmt = $conn->prepare("Insert into osoby (name, surname, birth_day, birth_place, birth_country, death_day, death_place, death_country) values (:name, :surname, :birth_day, :birth_place, :birth_country, :death_day, :death_place, :death_country)");        
    }
    if($bolSomTu == false){
        $pieces = explode("-", $_POST['birth_day']);
        $dateBirth = $pieces[2].".".$pieces[1].".".$pieces[0];
        $pieces = explode("-", $_POST['death_day']);
        $dateDeath = $pieces[2].".".$pieces[1].".".$pieces[0];
        $stmt->bindParam(":name", $_POST['name'], PDO::PARAM_STR);
        $stmt->bindParam(":surname", $_POST['surname'], PDO::PARAM_STR); 
        $stmt->bindParam(":birth_day", $dateBirth, PDO::PARAM_STR);
        $stmt->bindParam(":birth_place", $_POST['birth_place'], PDO::PARAM_STR);
        $stmt->bindParam(":birth_country", $_POST['birth_country'], PDO::PARAM_STR);
        if($dateDeath == "01.01.1970" || isset($_POST['death_day'])){
            $dateDeath = null;
            $stmt->bindParam(":death_day", $dateDeath, PDO::PARAM_STR);
        }else{
            $stmt->bindParam(":death_day", $dateDeath, PDO::PARAM_STR);
        }
        $stmt->bindParam(":death_place", $_POST['death_place'], PDO::PARAM_STR);
        $stmt->bindParam(":death_country", $_POST['death_country'], PDO::PARAM_STR);
        $stmt->execute();
    };
}

if (isset($_GET['id'])) {
    $getId = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM osoby WHERE osoby.id  = $getId");
    $stmt->execute();
    $row = $stmt->fetch();
}

?>

<body>
    <h1>Edit Person</h1>
    <form method="post" action="edit.php<?php echo isset($row) ? '?id='.$row["id"] : ''  ?>">

        <input type="hidden" name="id" value="<?php echo isset($row) ? $row["id"] : null ?>">


        <label for="name">Meno</label>
        <input type="text" name="name" id="name" value="<?php echo isset($row) ? $row["name"] : null ?>">
        <br>
        <label for="surname">Priezvisko</label>
        <input type="text" name="surname" id="surname" value="<?php echo isset($row) ? $row["surname"] : null ?>">
        <br>

        <?php
        if (isset($row)){
            $asStringBirth = $row["birth_day"];
            $toTimeBirth = strtotime($asStringBirth);
            $toDateBirth = date("Y-m-d", $toTimeBirth);
        }
        // else{
        //     $toTimeBirth = null;
        //     $toDateBirth = date("Y-m-d", $toTimeBirth);
        // }
        ?>
        <label for="birth_day">Dátum narodenia</label>
        <input type="date" id="birth_day" name="birth_day" value="<?php echo $toDateBirth?>" min="1800-01-01" max="2030-01-01">

        <br>
        <label for="birth_place">Miesto narodenia</label>
        <input type="text" name="birth_place" id="birth_place" value="<?php echo isset($row) ? $row["birth_place"] : null ?>">
        <br>
        <label for="birth_country">Krajina narodenia</label>
        <input type="text" name="birth_country" id="birth_country" value="<?php echo isset($row) ? $row["birth_country"] : null ?>">
        <br>
        <?php
        if (isset($row)){
            $asStringDeath = $row["death_day"];
            $toTimeDeath = strtotime($asStringDeath);
            $toDateDeath = date("Y-m-d", $toTimeDeath);
        }
        // else{
        //     $toTimeDeath = null;
        //     $toDateDeath = date("Y-m-d", $toTimeDeath);
        // }
        ?>
        <label for="death_day">Dátum úmrtia</label>
        <input type="date" id="death_day" name="death_day" value="<?php echo $toDateDeath?>" min="1800-01-01" max="2030-01-01">
        <br>
        <label for="death_place">Miesto úmrtia</label>
        <input type="text" name="death_place" id="death_place" value="<?php echo isset($row) ? $row["death_place"] : null ?>">
        <br>
        <label for="death_country">Krajina úmrtia</label>
        <input type="text" name="death_country" id="death_country" value="<?php echo isset($row) ? $row["death_country"] : null ?>">
        <br>
        <input type="submit">
    </form>
</body>

</html>