<?php

session_start();
if (!isset($_SESSION['user_id'])) {
    
    header("Location: login.html");
    exit();
}


$servername = "mysql8";
$username = "37328198_fermy";
$password = "R&b^7C!pD*2@";
$dbname = "37328198_fermy";


$id_fermy = $_GET['id'];


if (isset($id_fermy)) {
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Błąd połączenia z bazą danych: " . $conn->connect_error);
    }
    
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        $ilosc_pasz_starter = $_POST['ilosc_pasz_starter'];
        $ilosc_pasz_grower = $_POST['ilosc_pasz_grower'];
        $ilosc_pasz_finisher = $_POST['ilosc_pasz_finisher'];
        
        
        $sql = "UPDATE pasza SET
                ilosc_paszy = CASE
                    WHEN nazwa_paszy = 'starter' THEN '$ilosc_pasz_starter'
                    WHEN nazwa_paszy = 'grower' THEN '$ilosc_pasz_grower'
                    WHEN nazwa_paszy = 'finisher' THEN '$ilosc_pasz_finisher'
                END
                WHERE id_fermy = '$id_fermy' AND
                nazwa_paszy IN ('starter', 'grower', 'finisher')";
        
        
        if ($conn->query($sql) === TRUE) {
            header("Location: informacje_fermy.php?id=$id_fermy");
            exit();
        } else {
            echo "Błąd podczas aktualizacji rekordów: " . $conn->error;
        }
    }

    
    $query = "SELECT ilosc_paszy FROM pasza WHERE id_fermy='$id_fermy' AND nazwa_paszy='starter'";
    $result = $conn->query($query);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $starter = $row['ilosc_paszy'];
    } else {
        $starter = '';
    }

    $query = "SELECT ilosc_paszy FROM pasza WHERE id_fermy='$id_fermy' AND nazwa_paszy='grower'";
    $result = $conn->query($query);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $grower = $row['ilosc_paszy'];
    } else {
        $grower = '';
    }

    $query = "SELECT ilosc_paszy FROM pasza WHERE id_fermy='$id_fermy' AND nazwa_paszy='finisher'";
    $result = $conn->query($query);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $finisher = $row['ilosc_paszy'];
    } else {
        $finisher = '';
    }

    
    $conn->close();
}
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
        <input type="text" id="ilosc_pasz_starter" name="ilosc_pasz_starter" value="<?php echo $starter ?>"><br><br>
        
        <label for="ilosc_pasz_grower">Ilość paszy grower:</label>
        <input type="text" id="ilosc_pasz_grower" name="ilosc_pasz_grower" value="<?php echo $grower ?>"><br><br>
        
        <label for="ilosc_pasz_finisher">Ilość paszy finisher:</label>
        <input type="text" id="ilosc_pasz_finisher" name="ilosc_pasz_finisher" value="<?php echo $finisher ?>"><br><br>
        
        <button type="submit" name="submit">Zapisz ilość paszy</button>
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
