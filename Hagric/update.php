<?php
// Dane do połączenia z bazą danych
$host = "mysql8";
$dbname = "37328198_fermy";
$username = "37328198_fermy";
$password = "R&b^7C!pD*2@";

// Łączenie z bazą danych
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Nie można połączyć się z bazą danych: " . $e->getMessage());
}

// Aktualizacja wartości w kolumnie
try {
    // Pobranie wszystkich wartości z kolumny
    $stmt = $pdo->prepare("SELECT dzien_tuczu FROM stada");
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Aktualizacja każdej wartości
    foreach ($rows as $row) {
        $currentValue = $row['dzien_tuczu'];
        $newValue = $currentValue + 1;

        // Aktualizacja wartości w bazie danych
        $stmt = $pdo->prepare("UPDATE stada SET dzien_tuczu = :value WHERE dzien_tuczu = :currentValue");
        $stmt->execute([':value' => $newValue, ':currentValue' => $currentValue]);
    }

    echo "Wartości zostały zaktualizowane";
} catch (PDOException $e) {
    echo "Błąd wykonania zapytania: " . $e->getMessage();
    exit();
}
?>
