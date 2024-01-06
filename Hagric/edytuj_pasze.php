<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

require_once('db_config.php');

$id_fermy = $_GET['id'];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Nie można połączyć się z bazą danych: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ilosc_pasz_starter = (int)$_POST['ilosc_pasz_starter'];
    $ilosc_pasz_grower = (int)$_POST['ilosc_pasz_grower'];
    $ilosc_pasz_finisher = (int)$_POST['ilosc_pasz_finisher'];

    if ($_POST['submit'] === 'subtract_all') {
        $ilosc_pasz_starter = -$ilosc_pasz_starter;
        $ilosc_pasz_grower = -$ilosc_pasz_grower;
        $ilosc_pasz_finisher = -$ilosc_pasz_finisher;
    }

    $sql = "UPDATE pasza SET
            ilosc_paszy = CASE
                WHEN nazwa_paszy = 'starter' THEN ilosc_paszy + :ilosc_pasz_starter
                WHEN nazwa_paszy = 'grower' THEN ilosc_paszy + :ilosc_pasz_grower
                WHEN nazwa_paszy = 'finisher' THEN ilosc_paszy + :ilosc_pasz_finisher
            END
            WHERE id_fermy = :id_fermy AND
            nazwa_paszy IN ('starter', 'grower', 'finisher')";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':ilosc_pasz_starter', $ilosc_pasz_starter, PDO::PARAM_INT);
        $stmt->bindParam(':ilosc_pasz_grower', $ilosc_pasz_grower, PDO::PARAM_INT);
        $stmt->bindParam(':ilosc_pasz_finisher', $ilosc_pasz_finisher, PDO::PARAM_INT);
        $stmt->bindParam(':id_fermy', $id_fermy, PDO::PARAM_INT);
        $stmt->execute();
        header("Location: informacje_fermy.php?id=$id_fermy");
        exit();
    } catch (PDOException $e) {
        echo "Błąd podczas aktualizacji rekordów: " . $e->getMessage();
    }
}

// Pobranie ilości paszy dla każdej kategorii
$query = "SELECT ilosc_paszy FROM pasza WHERE id_fermy=:id_fermy AND nazwa_paszy='starter'";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id_fermy', $id_fermy, PDO::PARAM_INT);
$stmt->execute();
$starter = $stmt->fetchColumn();

$query = "SELECT ilosc_paszy FROM pasza WHERE id_fermy=:id_fermy AND nazwa_paszy='grower'";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id_fermy', $id_fermy, PDO::PARAM_INT);
$stmt->execute();
$grower = $stmt->fetchColumn();

$query = "SELECT ilosc_paszy FROM pasza WHERE id_fermy=:id_fermy AND nazwa_paszy='finisher'";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id_fermy', $id_fermy, PDO::PARAM_INT);
$stmt->execute();
$finisher = $stmt->fetchColumn();

?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="zglosupadek.css"/>
    <link href="css/footer.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,600,0,0" />
    <title>Edytuj ilość paszy</title>
    <style>
    body {
        background: linear-gradient(to right, #ff8800, #7c2e00);
    }
    .login-card button {
    margin-top: 1px;
    background: rgb(255, 115, 0);
    color: white;
    padding: 7px;
    border-radius: 100px;
    text-align: center;
    text-transform: uppercase;
    letter-spacing: 2px;
    transition: background .5s;
    }
    </style>
</head>
<body>
    <div class="login-card-container">
        <div class="login-card">
            <h2>Edytuj ilość paszy</h2>
            <a href='informacje_fermy.php?id=<?php echo $id_fermy; ?>' class='powrot'; style='color: black;'>Powrót</a>
            <a href='logout.php' class='wyloguj'; style='color: black;'>Wyloguj</a>

            <form method="POST" action="">
                <label for="ilosc_pasz_starter">Ilość paszy starter:</label>
                <input type="text" id="ilosc_pasz_starter" name="ilosc_pasz_starter"><br><br>

                <label for="ilosc_pasz_grower">Ilość paszy grower:</label>
                <input type="text" id="ilosc_pasz_grower" name="ilosc_pasz_grower"><br><br>

                <label for="ilosc_pasz_finisher">Ilość paszy finisher:</label>
                <input type="text" id="ilosc_pasz_finisher" name="ilosc_pasz_finisher"><br><br>

                <button type="submit" name="submit" value="add_all">Dodaj</button>
                <button type="submit" name="submit" value="subtract_all">Odejmij</button><br><br>
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
