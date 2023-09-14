<?php

$idZgloszenia = $_GET['id'];


require_once('db_config.php');

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Nie można połączyć się z bazą danych: " . $e->getMessage());
}


try {

    $query = "DELETE FROM zgloszenia_problemow WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $idZgloszenia);
    $stmt->execute();

    header("Location: zgloszenia.php");
    exit();
} catch (PDOException $e) {
    die("Błąd połączenia z bazą danych: " . $e->getMessage());
}
