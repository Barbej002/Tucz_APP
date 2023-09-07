<?php
// Import pliku z konfiguracją bazy danych
require_once 'db_config.php'; // Zakładam, że masz plik z konfiguracją bazy danych

// Sprawdzamy, czy formularz został przesłany
if (isset($_POST['submit'])) {
    // Pobieramy dane z formularza
    $data = $_POST['data'];
    $id_stada = $_POST['id_stada'];
    $preparat = $_POST['preparat'];
    $dawkowanie = $_POST['dawkowanie'];
    $okres = $_POST['okres'];
    $opinia = $_POST['opinia'];

    // Tworzymy zapytanie SQL do dodania danych do tabeli
    $sql = "INSERT INTO zgloszenia_leczen (data, id_stada, preparat, dawkowanie, okres, opinia)
            VALUES ('$data', '$id_stada', '$preparat', '$dawkowanie', '$okres', '$opinia')";


    // Wykonujemy zapytanie do bazy danych
    $id_fermy = $_GET['id'];
    if (mysqli_query($conn, $sql)) {
        header("Location: informacje_fermy.php?id=$id_fermy");
    } else {
        echo "Błąd: " . mysqli_error($conn);
    }

    // Zamykamy połączenie z bazą danych
    mysqli_close($conn);
}

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
    <title>Zgłoś leczenie</title>
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
                <h1>Zgłoś leczenie</h1>
            </div>
            <a href='informacje_fermy.php?id=<?php echo $idf; ?>' class='powrot'; style='color: black;'>Powrót</a>
            <a href='logout.php' class='wyloguj'; style='color: black;'>Wyloguj</a>

            <form class="login-card-form" method="POST" enctype="multipart/form-data">
                <div class="form-item">
                    <label for="data">Data:</label>
                    <div class="in">
                        <input type="date" name="data" id="data" required>
                    </div>
                    <script>
                        // Pobierz dzisiejszą datę
                        var dzisiejszaData = new Date();
                        var dzien = dzisiejszaData.getDate();
                        var miesiac = dzisiejszaData.getMonth() + 1; // Miesiące są numerowane od 0 do 11, więc dodajemy 1
                        var rok = dzisiejszaData.getFullYear();

                        // Jeśli miesiąc lub dzień mają tylko jedną cyfrę, dodaj zero na początku
                        if (miesiac < 10) {
                            miesiac = "0" + miesiac;
                        }
                        if (dzien < 10) {
                            dzien = "0" + dzien;
                        }

                        var dzisiejszaDataString = rok + "-" + miesiac + "-" + dzien;

                        // Ustaw wartość pola input type="date" na dzisiejszą datę
                        document.getElementById("data").value = dzisiejszaDataString;
                    </script>
                    <input type="hidden" name="id_stada" value="<?php echo $id_stada; ?>">

                    <label for="stado">Stado:</label>
                <div class="select-dropdown">
                    <?php
                    // Pobieramy informacje o stadach na podstawie id_farmy z parametru id w adresie URL
if (isset($_GET['id'])) {
    $id_fermy = $_GET['id'];

    // Tworzymy zapytanie SQL do pobrania danych o stadach danej farmy
    $sql = "SELECT * FROM stada WHERE id_farmy = '$id_fermy'";

    // Wykonujemy zapytanie do bazy danych
    $result = mysqli_query($conn, $sql);

    // Tworzymy listę rozwijaną z dostępnymi stadami
    echo "<select name='id_stada' id='stado' required>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<option value='" . $row['id'] . "'>" . $row['numer_stada'] . "</option>";
    }
    echo "</select>";

    // Zamykamy połączenie z bazą danych
    mysqli_close($conn);
}
                    ?>

                    </div>
                    <label for="preparat">Preparat:</label>
                    <input type="text" name="preparat" id="preparat" required>

                    <label for="dawkowanie">Dawkowanie:</label>
                    <input type="text" name="dawkowanie" id="dawkowanie" required>

                    <label for="okres">Okres (opcjonalnie):</label>
                    <input type="text" name="okres" id="okres">

                    <label for="okres">Opinia (opcjonalnie):</label>
                    <input type="text" name="opinia" id="opinia">

                    </div>
                    <button type="submit" name="submit">Zgłoś leczenie</button>
                </div>
            </form>
        </div>
    </div>
    <?php
    echo "<a href='informacje_fermy.php?id=$id_fermy' class='powrot'; style='color: black;'>Powrót</a>";
    echo "<a href='logout.php' class='wyloguj'; style='color: black;'>Wyloguj</a>";
    ?>
</body>

</html>
