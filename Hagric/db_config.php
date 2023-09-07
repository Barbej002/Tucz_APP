<?php
// Dane do połączenia z bazą danych
$host = "mysql8"; // Adres hosta bazy danych
$username = "37328198_fermy"; // Nazwa użytkownika bazy danych
$password = "R&b^7C!pD*2@"; // Hasło użytkownika bazy danych
$database = "37328198_fermy"; // Nazwa bazy danych

// Tworzenie połączenia z bazą danych
$conn = mysqli_connect($host, $username, $password, $database);

// Sprawdzanie połączenia
if (!$conn) {
    die("Błąd połączenia z bazą danych: " . mysqli_connect_error());
}
?>
