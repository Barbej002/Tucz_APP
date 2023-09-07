<?php
// Sprawdzenie czy użytkownik jest zalogowany
session_start();
if (!isset($_SESSION['user_id'])) {
    // Przekierowanie na stronę logowania
    header("Location: login.html");
    exit();
}
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
// Sprawdzenie, czy zostało przekazane ID stada
if (isset($_GET['id']) && isset($_GET['idf'])) {
    $id = $_GET['id'];
    $idf = $_GET['idf'];
    $id_stada = $_GET['id']; // Dodane przypisanie wartości do zmiennej $id_stada
} else {
    // Jeżeli brak ID stada, przekieruj użytkownika lub wyświetl komunikat błędu
    header("Location: informacje_fermy.php?id=$idf");
    exit();
}

// Sprawdzenie, czy formularz został wysłany
if (isset($_POST['submit'])) {
    // Pobranie danych z formularza
    $ilosc_padlych = $_POST['ilosc_padlych'];
    $data = $_POST['data'];
    $przyczyna = $_POST['przyczyna'];
    $opiniujacy = $_POST['opiniujacy'];

    // Pobranie i zapisanie przesłanych plików
    $files = $_FILES['files'];
    $file_names = $files['name'];
    $file_tmps = $files['tmp_name'];
    $file_errors = $files['error'];

    $uploaded_files = array(); // Tablica przechowująca nazwy przesłanych plików

    // Iteracja przez przesłane pliki
    for ($i = 0; $i < count($file_names); $i++) {
        // Sprawdzenie, czy wystąpił błąd w przesyłaniu pliku
        if ($file_errors[$i] === 0) {
            // Przeniesienie pliku do docelowej lokalizacji
            $destination = "upload/" . $file_names[$i];
            move_uploaded_file($file_tmps[$i], $destination);
            $uploaded_files[] = $destination; // Dodanie ścieżki do tablicy przesłanych plików
        }
    }

    // Wstawienie danych do tabeli "informacje_upadki"
    $query = "INSERT INTO informacje_upadki (id_stada, ilosc_padlych, data, przyczyna, opiniujacy) 
              VALUES (:id_stada, :ilosc_padlych, :data, :przyczyna, :opiniujacy)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id_stada', $id_stada);
    $stmt->bindParam(':ilosc_padlych', $ilosc_padlych);
    $stmt->bindParam(':data', $data);
    $stmt->bindParam(':przyczyna', $przyczyna);
    $stmt->bindParam(':opiniujacy', $opiniujacy);
    $stmt->execute();

    // Pobranie ID ostatnio wstawionego rekordu
    $last_insert_id = $pdo->lastInsertId();

    // Iteracja przez przesłane pliki i zapisanie ich w bazie danych
    foreach ($uploaded_files as $file_path) {
        $file_name = basename($file_path); // Pobranie samej nazwy pliku

        // Wstawienie ścieżki pliku i nazwy pliku do tabeli "pliki_upadki"
        $query = "INSERT INTO pliki_upadki (id_upadku, sciezka, nazwa_pliku) VALUES (:id_upadku, :sciezka, :nazwa_pliku)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id_upadku', $last_insert_id);
        $stmt->bindParam(':sciezka', $file_path);
        $stmt->bindParam(':nazwa_pliku', $file_name);
        $stmt->execute();
    }

    // Aktualizacja kolumny "ilosc_padlych" w tabeli "stada"
    $query = "UPDATE stada SET ilosc_padlych = ilosc_padlych + :ilosc_padlych WHERE id = :id_stada";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id_stada', $id_stada);
    $stmt->bindParam(':ilosc_padlych', $ilosc_padlych);
    $stmt->execute();

    // Przekierowanie użytkownika na inną stronę po dodaniu informacji
    header("Location: informacje_fermy.php?id=$idf");
    exit();
}
echo "<a href='informacje_fermy.php?id=$idf' class='powrot'; style='color: black;'>Powrót</a>";
echo "<a href='logout.php' class='wyloguj'; style='color: black;'>Wyloguj</a>";
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="zglosupadek.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,600,0,0" />
    <link href="css/select.css" rel="stylesheet" type="text/css">
    <link href="css/footer.css" rel="stylesheet">
    <title>Zgłoś upadek</title>
    <style>
    body {
        background: linear-gradient(to right, #ff8800, #7c2e00);
    }

    .in input[type="date"] {
        width: 100%;
        padding: 10px;
        background-color: rgba(255, 255, 255, .3);
        border: 1px solid #ccc;
        border-radius: 25px;
        box-sizing: border-box;
    }
    .in input[type="file"] {
        width: 100%;
        padding: 10px;
        background-color: rgba(255, 255, 255, .3);
        border: 1px solid #ccc;
        border-radius: 25px;
        box-sizing: border-box;
    }
    </style>
</head>

<body>
    <div class="login-card-container">
        <div class="login-card">
            <div class="login-card-header">
                <h1>Zgłoś upadek</h1>
            </div>
            <a href='informacje_fermy.php?id=<?php echo $idf; ?>' class='powrot'; style='color: black;'>Powrót</a>
            <a href='logout.php' class='wyloguj'; style='color: black;'>Wyloguj</a>

            <form class="login-card-form" method="POST" enctype="multipart/form-data">
                <div class="form-item">
                    <input type="hidden" name="id_stada" value="<?php echo $id_stada; ?>">

                    <label for="ilosc_padlych">Ilość padłych:</label>
                    <input type="number" name="ilosc_padlych" id="ilosc_padlych" required>
                    <label for="data">Data:</label>
                    <div class="in">
                        <input type="date" name="data" id="data" required>
                    </div>
                    <label for="przyczyna">Przyczyna:</label>
                    <input type="text" name="przyczyna" id="przyczyna" required>

                    <label for="opiniujacy">Opiniujący:</label>
                    <div class="select-dropdown">
                        <select name="opiniujacy" id="opiniujacy" required>
                            <option value="">Wybierz opiniującego</option>
                            <option value="Lekarz">Lekarz wet.</option>
                            <option value="Klient">Klient</option>
                            <option value="Inne">Inne</option>
                            <!-- Dodaj więcej opcji wyboru tutaj -->
                        </select>
                    </div>

                    <label for="files">Dodaj pliki:</label>
                    <div class="in">
                    <input type="file" id="files" name="files[]" multiple>
                    </div>

                    <button type="submit" name="submit">Dodaj informację</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
