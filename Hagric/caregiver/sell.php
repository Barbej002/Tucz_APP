<?php
// Inicjalizacja zmiennych
$id_stada = $_GET['id'];
$id_fermy = $_GET['idf'];
$errors = array();
$default_liczba_sztuk = 0; // Domyślna wartość

// Połączenie z bazą danych
$conn = mysqli_connect("mysql8", "37328198_fermy", "R&b^7C!pD*2@", "37328198_fermy");

if ($conn->connect_error) {
    die("Błąd połączenia: " . $conn->connect_error);
}

// Pobierz numer_stada z tabeli stada
if (!is_null($id_stada)) {
    $sql_get_numer_stada = "SELECT numer_stada FROM stada WHERE id = ?";
    $stmt_get_numer_stada = mysqli_prepare($conn, $sql_get_numer_stada);

    if ($stmt_get_numer_stada) {
        mysqli_stmt_bind_param($stmt_get_numer_stada, "i", $id_stada);
        mysqli_stmt_execute($stmt_get_numer_stada);
        mysqli_stmt_bind_result($stmt_get_numer_stada, $numer_stada);

        mysqli_stmt_fetch($stmt_get_numer_stada);
        mysqli_stmt_close($stmt_get_numer_stada);
    }
}

// Pobierz ilość sztuk z danego stada
$sql_get_liczba_sztuk = "SELECT ilosc_sztuk FROM stada WHERE id = '$id_stada'";
$result_get_liczba_sztuk = mysqli_query($conn, $sql_get_liczba_sztuk);

if ($result_get_liczba_sztuk) {
    $row = mysqli_fetch_assoc($result_get_liczba_sztuk);
    $default_liczba_sztuk = $row['ilosc_sztuk'];
}

// Pobierz ilość padłych sztuk z danego stada
$sql_get_ilosc_padlych = "SELECT ilosc_padlych FROM stada WHERE id = '$id_stada'";
$result_get_ilosc_padlych = mysqli_query($conn, $sql_get_ilosc_padlych);

if ($result_get_ilosc_padlych) {
    $row = mysqli_fetch_assoc($result_get_ilosc_padlych);
    $default_ilosc_padlych = $row['ilosc_padlych'];
}

// Obsługa formularza
if (isset($_POST['submit'])) {
    $id_stada = $_POST['id_stada'];
    $id_fermy = $_POST['id_fermy'];
    $nazwa_ubojni = $_POST['nazwa_ubojni'];
    $date = $_POST['date'];
    $waga = $_POST['waga'];
    $liczba_sztuk_sprzedanych = $_POST['liczba_sztuk'];

    // Walidacja
    if (empty($nazwa_ubojni)) {
        array_push($errors, "Nazwa ubojni jest wymagana");
    }
    if (empty($waga)) {
        array_push($errors, "Waga jest wymagana");
    }

    if (empty($errors)) {
        // Przygotuj zapytanie SQL z wykorzystaniem prepared statement
        $sql = "INSERT INTO sprzedane (id_stada, id_fermy, nazwa_ubojni, numer_stada, date, waga, liczba_sztuk) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt === false) {
            die("Błąd przy przygotowywaniu zapytania: " . mysqli_error($conn));
        }

                    // Konwersja daty na format "YYYY-MM-DD"
        $date = date("Y-m-d", strtotime($date));

            // Podłącz parametry
            mysqli_stmt_bind_param($stmt, "iisssss", $id_stada, $id_fermy, $nazwa_ubojni, $numer_stada, $date, $waga, $liczba_sztuk_sprzedanych);

            // Wykonaj zapytanie
            if (mysqli_stmt_execute($stmt)) {
                // Zaktualizuj liczbę zwierząt w stadzie
                $sql_update_liczba_sztuk = "UPDATE stada SET ilosc_sztuk = ilosc_sztuk - ? WHERE id = ?";
                $stmt_update_liczba_sztuk = mysqli_prepare($conn, $sql_update_liczba_sztuk);

                if ($stmt_update_liczba_sztuk) {
                    mysqli_stmt_bind_param($stmt_update_liczba_sztuk, "ii", $liczba_sztuk_sprzedanych, $id_stada);
                    if (mysqli_stmt_execute($stmt_update_liczba_sztuk)) {
                        echo "Dane zostały dodane do bazy danych, a ilość sztuk w stadzie została zaktualizowana.";
                        header("Location: informacje_fermy.php?id=$id_fermy");
                        exit();
                    } else {
                        $errors[] = "Błąd podczas dodawania danych do bazy danych: " . mysqli_error($conn);
                    }
                    mysqli_stmt_close($stmt_update_liczba_sztuk);
                }
            } else {
                $errors[] = "Błąd podczas dodawania danych do bazy danych: " . mysqli_error($conn);
            }
            mysqli_stmt_close($stmt);
        }
    }


// Pobierz stada do wyboru w formularzu
$sql = "SELECT * FROM stada WHERE id_farmy = ?";
$stmt_herds = mysqli_prepare($conn, $sql);

if ($stmt_herds) {
    mysqli_stmt_bind_param($stmt_herds, "i", $id_fermy);
    mysqli_stmt_execute($stmt_herds);
    $result = mysqli_stmt_get_result($stmt_herds);
}

// Zamknij połączenie z bazą danych
mysqli_close($conn);
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
    <title>Zgłoś sprzedaż</title>
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
                <h1>Zgłoś sprzedaż</h1>
            </div>
            <a href='informacje_fermy.php?id=<?php echo $id_fermy; ?>' class='powrot'; style='color: black;'>Powrót</a>
            <a href='logout.php' class='wyloguj'; style='color: black;'>Wyloguj</a>

            <form class="login-card-form" method="POST" enctype="multipart/form-data">
                <div class="form-item">
                    <input type="hidden" name="id_stada" value="<?php echo $id_stada; ?>">
                    <input type="hidden" name="id_fermy" value="<?php echo $id_fermy; ?>">

                <label for="nazwa_ubojni">Nazwa ubojni:</label>
                <input type="text" name="nazwa_ubojni" id="nazwa_ubojni" required>

                <label for="data">Data:</label>
                    <div class="in">
                        <input type="date" name="date" id="date" required>
                    </div>
                    <script>
                        var dzisiejszaData = new Date();
                        var dzien = dzisiejszaData.getDate();
                        var miesiac = dzisiejszaData.getMonth() + 1;
                        var rok = dzisiejszaData.getFullYear();

                        if (miesiac < 10) {
                            miesiac = "0" + miesiac;
                        }
                        if (dzien < 10) {
                            dzien = "0" + dzien;
                        }

                        var dzisiejszaDataString = rok + "-" + miesiac + "-" + dzien;
                        document.getElementById("date").value = dzisiejszaDataString;
                    </script>

                <label for="waga">Waga:</label>
                <input type="number" step="0.01" name="waga" id="waga" required>

                <label for="liczba_sztuk">Liczba sztuk:</label>
                <input type="text" name="liczba_sztuk" id="liczba_sztuk" value="<?php echo ($default_liczba_sztuk - $default_ilosc_padlych); ?>">

                </div>
                <button type="submit" name="submit">Zgłoś sprzedaż</button>
            </div>
        </form>
    </div>
</div>
</body>

</html>