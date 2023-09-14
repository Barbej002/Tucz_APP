<?php

require_once('db_config.php');

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Nie można połączyć się z bazą danych: " . $e->getMessage());
}



try {

    $stmt = $pdo->prepare("SELECT dzien_tuczu FROM stada");
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);


    foreach ($rows as $row) {
        $currentValue = $row['dzien_tuczu'];
        $newValue = $currentValue + 1;


        $stmt = $pdo->prepare("UPDATE stada SET dzien_tuczu = :value WHERE dzien_tuczu = :currentValue");
        $stmt->execute([':value' => $newValue, ':currentValue' => $currentValue]);
    }

    echo "Wartości zostały zaktualizowane";
} catch (PDOException $e) {
    echo "Błąd wykonania zapytania: " . $e->getMessage();
    exit();
}
?>
