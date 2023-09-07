<?php
// Pobranie ID zgłoszenia do usunięcia
$idZgloszenia = $_GET['id'];

// Dane do połączenia z bazą danych
$host = "mysql8";
$dbname = "37328198_fermy";
$username = "37328198_fermy";
$password = "R&b^7C!pD*2@";

try {
    // Połączenie z bazą danych
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Usunięcie zgłoszenia z bazy danych
    $query = "DELETE FROM zgloszenia_problemow WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $idZgloszenia);
    $stmt->execute();

    // Przekierowanie na stronę z listą zgłoszeń
    header("Location: zgloszenia.php");
    exit();
} catch (PDOException $e) {
    die("Błąd połączenia z bazą danych: " . $e->getMessage());
}
