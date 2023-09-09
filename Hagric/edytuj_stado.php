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

$query = "SELECT * FROM stada WHERE id=$id";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ilosc_sztuk = $_POST['ilosc_sztuk'];
    $ilosc_padlych = $_POST['ilosc_padlych'];
    $przyczyna = $_POST['przyczyna'];
    $opinia = $_POST['opinia'];

    $query = "UPDATE stada SET ilosc_sztuk=$ilosc_sztuk, ilosc_padlych=$ilosc_padlych, przyczyna='$przyczyna', opinia='$opinia' WHERE id=$id";
    $result = mysqli_query($conn, $query);
    if ($result) {
        header("Location: index.php");
        exit();
    } else {
        echo "Wystąpił błąd podczas aktualizacji danych.";
    }
}

echo "<div class='login-card-header'>";
echo "<h1>Edycja danych stada</h1>";
echo "</div>";
echo "<form class='login-card-form' method='POST'>";
echo "<div class='form-item'>";
echo "<p><label for='ilosc_sztuk'>Ilość sztuk:</label>";
echo "<input type='number' name='ilosc_sztuk' value='" . $row['ilosc_sztuk'] . "'></p>";
echo "<p><label for='ilosc_padlych'>Ilość padłych sztuk:</label>";
echo "<input type='number' name='ilosc_padlych' value='" . $row['ilosc_padlych'] . "'></p>";
echo "<p><label for='przyczyna'>Przyczyna zgonu:</label>";
echo "<input type='text' name='przyczyna' value='" . $row['przyczyna'] . "'></p>";
echo "<p><label for='opinia'>Opinia:</label>";
echo "<input type='text' name='opinia' value='" . $row['opinia'] . "'></p>";
echo "<button type='submit'>Zapisz zmiany</button>";
echo "</form>";
echo "<a href='informacje_fermy.php?id=$id' class='powrot'>Powrót</a>";
?>

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
