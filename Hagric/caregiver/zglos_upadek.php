<?php

session_start();
if (!isset($_SESSION['user_id'])) {
    
    header("Location: login.html");
    exit();
}

require_once('db_config.php');

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Nie można połączyć się z bazą danych: " . $e->getMessage());
}


if (isset($_GET['id']) && isset($_GET['idf'])) {
    $id = $_GET['id'];
    $idf = $_GET['idf'];
    $id_stada = $_GET['id']; 
} else {

    header("Location: informacje_fermy.php?id=$idf");
    exit();
}


if (isset($_POST['submit'])) {

    $ilosc_padlych = $_POST['ilosc_padlych'];
    $data = $_POST['data'];
    $przyczyna = $_POST['przyczyna'];
    if ($przyczyna === 'inne') {
        // Jeśli przyczyna to "inne", pobierz wartość z pola "Inna przyczyna"
        $inne_przyczyna = $_POST['inne'];
        $przyczyna = $inne_przyczyna; // Zaktualizuj zmienną $przyczyna
    }
    $opiniujacy = $_POST['opiniujacy'];


    $files = $_FILES['files'];
    $file_names = $files['name'];
    $file_tmps = $files['tmp_name'];
    $file_errors = $files['error'];

    $uploaded_files = array(); 


    for ($i = 0; $i < count($file_names); $i++) {
        
        if ($file_errors[$i] === 0) {
            
            $destination = "upload/" . $file_names[$i];
            move_uploaded_file($file_tmps[$i], $destination);
            $uploaded_files[] = $destination; 
        }
    }


    $query = "INSERT INTO informacje_upadki (id_stada, ilosc_padlych, data, przyczyna, opiniujacy) 
              VALUES (:id_stada, :ilosc_padlych, :data, :przyczyna, :opiniujacy)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id_stada', $id_stada);
    $stmt->bindParam(':ilosc_padlych', $ilosc_padlych);
    $stmt->bindParam(':data', $data);
    $stmt->bindParam(':przyczyna', $przyczyna);
    $stmt->bindParam(':opiniujacy', $opiniujacy);
    $stmt->execute();


    $last_insert_id = $pdo->lastInsertId();


    foreach ($uploaded_files as $file_path) {
        $file_name = basename($file_path); 


        $query = "INSERT INTO pliki_upadki (id_upadku, sciezka, nazwa_pliku) VALUES (:id_upadku, :sciezka, :nazwa_pliku)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id_upadku', $last_insert_id);
        $stmt->bindParam(':sciezka', $file_path);
        $stmt->bindParam(':nazwa_pliku', $file_name);
        $stmt->execute();
    }


    $query = "UPDATE stada SET ilosc_padlych = ilosc_padlych + :ilosc_padlych WHERE id = :id_stada";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id_stada', $id_stada);
    $stmt->bindParam(':ilosc_padlych', $ilosc_padlych);
    $stmt->execute();


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
        <script>
        // Funkcja wywoływana po zmianie wartości w polu przyczyny
        function toggleOtherReasonField() {
            // Pobranie wartości wybranej opcji
            var selectedReason = document.getElementById("przyczyna").value;
            
            // Pobranie pola "inne_przyczyna"
            var otherReasonField = document.getElementById("inne");

            // Jeśli wybrano opcję "INNE", pokaż pole "inne_przyczyna", w przeciwnym razie ukryj
            if (selectedReason === "inne") {
                otherReasonField.style.display = "block";
                otherReasonField.setAttribute("required", "true"); // Ustawienie pola jako wymaganego
            } else {
                otherReasonField.style.display = "none";
                otherReasonField.removeAttribute("required"); // Usunięcie wymagalności pola
            }
        }
    </script>
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
                    <div class="select-dropdown">
                        <select name="przyczyna" id="przyczyna" onchange="toggleOtherReasonField()" required>
                            <option value="">Wybierz przyczynę</option>
                            <option value="Strepto">Strepto</option>
                            <option value="Glasser">Glasser</option>
                            <option value="Kolibakterioza">Kolibakterioza</option>
                            <option value="Wrzody">Wrzody</option>
                            <option value="Beztlenowiec">Beztlenowiec</option>
                            <option value="Kanibalizm">Kanibalizm</option>
                            <option value="APP">APP</option>
                            <option value="Prrs">Prrs</option>
                            <option value="Charłaczy">Charłaczy</option>
                            <option value="PCV">PCV</option>
                            <option value="inne">Inne</option>
                            
                        </select>
                    </div>
                    <div id="inne" style="display: none;">
                        <label for="inne">Inna przyczyna:</label>
                        <input type="text" name="inne" id="inne">
                    </div>

                    <label for="opiniujacy">Opiniujący:</label>
                    <div class="select-dropdown">
                        <select name="opiniujacy" id="opiniujacy" required>
                            <option value="">Wybierz opiniującego</option>
                            <option value="Lekarz">Lekarz wet.</option>
                            <option value="Klient">Klient</option>
                            <option value="Inne">Inne</option>
                            
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
