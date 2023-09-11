<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="zglosupadek.css"/>
    <link href="css/footer.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,600,0,0" />
    <title>Edytuj stado</title>
    <style>
            body {
        background: linear-gradient(to right, #ff8800, #7c2e00);
    }
    </style>
</head>
<body>
<div class="login-card-container">
<div class="login-card">
<?php

session_start();
if (!isset($_SESSION['user_id'])) {
    
    header("Location: login.html");
    exit();
}


$id = $_GET['id'];


$conn = mysqli_connect("mysql8", "37328198_fermy", "R&b^7C!pD*2@", "37328198_fermy");


if (isset($_POST['submit'])) {
    
    $nazwa = $_POST['nazwa'];
    $wlasciciel = $_POST['wlasciciel'];
    $adres = $_POST['adres'];
    $kontakt = $_POST['kontakt'];
    $numer_s = $_POST['numer_s'];
    $przedstawiciel_hagric = $_POST['przedstawiciel_hagric'];
    $dostawca_pasz = $_POST['dostawca_pasz'];
    $przedstawiciel_dostawcy_pasz = $_POST['przedstawiciel_dostawcy_pasz'];

    
    $query = "UPDATE lista_ferm SET nazwa='$nazwa', wlasciciel='$wlasciciel', adres='$adres', kontakt='$kontakt', numer_s='$numer_s', przedstawiciel_hagric='$przedstawiciel_hagric', dostawca_pasz='$dostawca_pasz', przedstawiciel_dostawcy_pasz='$przedstawiciel_dostawcy_pasz' WHERE id=$id";
    mysqli_query($conn, $query);

    
    header("Location: informacje_fermy.php?id=$id");
    exit();
}


$query = "SELECT * FROM lista_ferm WHERE id=$id";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
echo "<a href='informacje_fermy.php?id=$id' class='powrot'; style='color: black;'>Powrót</a>";
?>



<h1>Edytuj fermę</h1>

<form method="post">
    <label for="nazwa">Nazwa:</label>
    <input type="text" name="nazwa" value="<?php echo $row['nazwa']; ?>"><br>

    <label for="wlasciciel">Właściciel:</label>
    <input type="text" name="wlasciciel" value="<?php echo $row['wlasciciel']; ?>"><br>
    
    <label for="adres">Adres:</label>
    <input type="text" name="adres" value="<?php echo $row['adres']; ?>"><br>

    <label for="kontakt">Kontakt:</label>
    <input type="text" name="kontakt" value="<?php echo $row['kontakt']; ?>"><br>
    
    <label for="numer_s">Numer siedziby stada:</label>
    <input type="text" name="numer_s" value="<?php echo $row['numer_s']; ?>"><br>

    <label for="przedstawiciel_hagric">Przedstawiciel Hagric:</label>
    <input type="text" name="przedstawiciel_hagric" value="<?php echo $row['przedstawiciel_hagric']; ?>"><br>

    <label for="dostawca_pasz">Dostawca pasz:</label>
    <input type="text" name="dostawca_pasz" value="<?php echo $row['dostawca_pasz']; ?>"><br>
    
    <label for="przedstawiciel_dostawcy_pasz">Przedstawiciel dostawcy pasz:</label>
    <input type="text" name="przedstawiciel_dostawcy_pasz" value="<?php echo $row['przedstawiciel_dostawcy_pasz']; ?>"><br>

    <button type="submit" name="submit">Zapisz zmiany</button>
</form>
</div>
</div>
<a href="logout.php" class="wyloguj">Wyloguj</a>

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
