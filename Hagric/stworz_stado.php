<?php
// Sprawdzenie czy użytkownik jest zalogowany
session_start();
if (!isset($_SESSION['user_id'])) {
    // Przekierowanie na stronę logowania
    header("Location: login.html");
    exit();
}

// Pobranie ID fermy z adresu URL
$id_fermy = $_GET['id'];

// Nazwy pasz
$starter = "starter";
$grower = "grower";
$finisher = "finisher";

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

// Sprawdzenie czy pasze dla danej fermy zostały już stworzone
$stmt = $pdo->prepare("SELECT COUNT(*) AS count FROM pasza WHERE id_fermy = :id_fermy");
$stmt->execute([':id_fermy' => $id_fermy]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);


// Sprawdzenie czy formularz został wysłany
if (isset($_POST['submit'])) {
    // Pobranie danych z formularza
    $numer_stada = $_POST['numer_stada'];
    $opis = $_POST['opis'];
    $ilosc_sztuk = $_POST['ilosc_sztuk'];
    $ilosc_padlych = $_POST['ilosc_padlych'];
    $dzien_tuczu = $_POST['dzien_tuczu'];

    // Rozpoczęcie transakcji
    $pdo->beginTransaction();

    try {
        // Sprawdzenie czy pasze już istnieją dla danej fermy
        $stmt = $pdo->prepare("SELECT COUNT(*) AS count FROM pasza WHERE id_fermy = :id_fermy");
        $stmt->execute([':id_fermy' => $id_fermy]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result['count'] == 0) {
            // Jeśli pasze nie istnieją, dodajemy je do tabeli "pasza"
            // Wstawienie startera do tabeli "pasza"
            $stmt = $pdo->prepare("INSERT INTO pasza (id_fermy, ilosc_paszy, nazwa_paszy) VALUES (:id_fermy, 0, :nazwa_paszy)");
            $stmt->execute([':id_fermy' => $id_fermy, ':nazwa_paszy' => $starter]);
            $id_startera = $pdo->lastInsertId();

            // Wstawienie growera do tabeli "pasza"
            $stmt->execute([':id_fermy' => $id_fermy, ':nazwa_paszy' => $grower]);
            $id_growera = $pdo->lastInsertId();

            // Wstawienie finishera do tabeli "pasza"
            $stmt->execute([':id_fermy' => $id_fermy, ':nazwa_paszy' => $finisher]);
            $id_finishera = $pdo->lastInsertId();
        }

        // Wstawienie danych do tabeli "stada"
        $stmt = $pdo->prepare("INSERT INTO stada (id_farmy, ilosc_sztuk, ilosc_padlych, numer_stada, opis, dzien_tuczu) VALUES (:id_fermy, :ilosc_sztuk, :ilosc_padlych, :numer_stada, :opis, :dzien_tuczu)");
        $stmt->execute([':id_fermy' => $id_fermy, ':ilosc_sztuk' => $ilosc_sztuk, ':ilosc_padlych' => $ilosc_padlych, ':numer_stada' => $numer_stada, ':opis' => $opis, ':dzien_tuczu' => $dzien_tuczu]);

        // Zatwierdzenie transakcji
        $pdo->commit();

        // Przekierowanie użytkownika na stronę z danymi na temat danej fermy
        header("Location: informacje_fermy.php?id=$id_fermy");
        exit();
    } catch (PDOException $e) {
        // Wycofanie transakcji w przypadku błędu
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
