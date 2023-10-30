<?php

session_start();
if (!isset($_SESSION['user_id'])) {
    
    header("Location: login.html");
    exit();
}


$id_fermy = $_GET['id'];


$starter = "starter";
$grower = "grower";
$finisher = "finisher";


require_once('db_config.php');

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Nie można połączyć się z bazą danych: " . $e->getMessage());
}



$stmt = $pdo->prepare("SELECT COUNT(*) AS count FROM pasza WHERE id_fermy = :id_fermy");
$stmt->execute([':id_fermy' => $id_fermy]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);



if (isset($_POST['submit'])) {
    
    $numer_stada = $_POST['numer_stada'];
    $opis = $_POST['opis'];
    $ilosc_sztuk = $_POST['ilosc_sztuk'];
    $ilosc_padlych = $_POST['ilosc_padlych'];
    $dzien_tuczu = $_POST['dzien_tuczu'];


    $pdo->beginTransaction();

    try {

        $stmt = $pdo->prepare("SELECT COUNT(*) AS count FROM pasza WHERE id_fermy = :id_fermy");
        $stmt->execute([':id_fermy' => $id_fermy]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result['count'] == 0) {

            $stmt = $pdo->prepare("INSERT INTO pasza (id_fermy, ilosc_paszy, nazwa_paszy) VALUES (:id_fermy, 0, :nazwa_paszy)");
            $stmt->execute([':id_fermy' => $id_fermy, ':nazwa_paszy' => $starter]);
            $id_startera = $pdo->lastInsertId();


            $stmt->execute([':id_fermy' => $id_fermy, ':nazwa_paszy' => $grower]);
            $id_growera = $pdo->lastInsertId();


            $stmt->execute([':id_fermy' => $id_fermy, ':nazwa_paszy' => $finisher]);
            $id_finishera = $pdo->lastInsertId();
        }


        $stmt = $pdo->prepare("INSERT INTO stada (id_farmy, ilosc_sztuk, ilosc_padlych, numer_stada, opis, dzien_tuczu) VALUES (:id_fermy, :ilosc_sztuk, :ilosc_padlych, :numer_stada, :opis, :dzien_tuczu)");
        $stmt->execute([':id_fermy' => $id_fermy, ':ilosc_sztuk' => $ilosc_sztuk, ':ilosc_padlych' => $ilosc_padlych, ':numer_stada' => $numer_stada, ':opis' => $opis, ':dzien_tuczu' => $dzien_tuczu]);


        $pdo->commit();


        header("Location: informacje_fermy.php?id=$id_fermy");
        exit();
    } catch (PDOException $e) {

        $pdo->rollBack();

        echo "Błąd wykonania zapytania: " . $e->getMessage();
        exit();
    }
}
echo "<a href='informacje_fermy.php?id=$id_fermy' class='powrot'>Powrót</a>";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,600,0,0" />
    <link href="css/footer.css" rel="stylesheet">
    <title>Dodaj stado</title>
</head>
<body>
    <div class="login-card-container">
        <div class="login-card">
            <div class="login-card-header">
                <h1>Dodaj stado</h1>
            </div>

            <form class="login-card-form" method="POST">
                <div class="form-item">
                    <label>Numer stada:</label>
                    <input type="text" name="numer_stada" required><br>
                    <label>Opis/komora:</label>
                    <input type="text" name="opis" required><br>
                    <label>Ilość sztuk:</label>
                    <input type="number" name="ilosc_sztuk" required><br>
                    <label>Dzień tuczu:</label>
                    <input type="number" name="dzien_tuczu"><br>
                    <button type="submit" name="submit">Dodaj stado</button>
                </div>
            </form>
        </div>
    </div>
    <div class="kontener">
        <div class="footer">
            <p><span class="footer-text">&copy;</span> <span class="current-year">Year</span> <span class="footer-text">Hagric - Developed by <a href="https://www.ac-it.pl/" target="_blank" class="footer-link">AC IT Sp. z o.o.</a></span></p>
        </div>
    </div>

    <script>
        const currentYear = new Date().getFullYear();
        document.querySelector(".current-year").textContent = currentYear;
    </script>
</body>
</html>
