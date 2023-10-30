<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="zglosupadek.css"/>
    <link href="css/footer.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,600,0,0" />
    <title>Dodaj opiekuna</title>
    <style>
        body {
            background: linear-gradient(to right, #ff8800, #7c2e00);
        }

        .farmers-container {
            max-height: 200px;
            overflow-y: auto;
            background: rgba(255, 255, 255, .3);
            padding: 1rem 1.5rem;
            border-radius: 5px;
        }
        .farmers-container label {
            display: block;
            margin-bottom: 5px;
        }


    </style>
</head>

<body>
    <div class="login-card-container">
        <div class="login-card">
            <a href='index.php' class='powrot' style='color: black;'>Powrót</a>

            <h1>Dodaj opiekuna:</h1>

            <form method="post" accept-charset="UTF-8">
                <label for="username">E-mail:</label>
                <input type="text" name="username" required><br>

                <label for="nip">NIP:</label>
                <input type="number" name="nip" required><br>

                <label for="password">Hasło:</label>
                <input type="password" name="password" required><br>

                <label for="numer_telefonu">Numer telefonu:</label>
                <input type="text" name="numer_telefonu" required><br>

                <label for="imie">Imię:</label>
                <input type="text" name="imie" required><br>

                <label for="nazwisko">Nazwisko:</label>
                <input type="text" name="nazwisko" required><br>

                <label for="adres">Adres:</label>
                <input type="text" name="adres" required><br>

                <label>Wybierz rolników:</label><br>
                <div class="farmers-container">
                    <?php
                    try {
                        $pdo = new PDO("mysql:host=mysql8;dbname=37328198_fermy;charset=utf8", '37328198_fermy', 'R&b^7C!pD*2@');
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        $query_farmers = "SELECT id, username FROM users WHERE administrator IS NULL";
                        $stmt_farmers = $pdo->prepare($query_farmers);
                        $stmt_farmers->execute();
                        $farmers = $stmt_farmers->fetchAll();

                        foreach ($farmers as $farmer) {
                            echo "<label><input type='checkbox' name='farmers[]' value='" . $farmer['id'] . "' >" . $farmer['username'] . "</label><br>";
                        }
                    } catch (PDOException $e) {
                        echo "Błąd zapytania: " . $e->getMessage();
                    }
                    ?>
                </div>

                <button type="submit" name="submit">Dodaj</button>
            </form>
        


    <?php
    if (isset($_POST['submit'])) {
        require_once('db_config.php');

        try {
            $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Zbieranie danych z formularza
            $username = $_POST['username'];
            $nip = $_POST['nip'];
            $password = $_POST['password'];
            $numer_telefonu = $_POST['numer_telefonu'];
            $imie = $_POST['imie'];
            $nazwisko = $_POST['nazwisko'];
            $adres = $_POST['adres'];
            // Pozostałe zmienne z formularza

            $selectedFarmers = $_POST['farmers'] ?? []; // Pobranie zaznaczonych rolników

            $pdo->beginTransaction();

            // Wstawienie danych opiekuna do bazy
            $query_insert_caregiver = "INSERT INTO users (username, nip, password, numer_telefonu, imie, nazwisko, adres, administrator) VALUES (:username, :nip, :password, :numer_telefonu, :imie, :nazwisko, :adres, 'caregiver')";
            $stmt_insert_caregiver = $pdo->prepare($query_insert_caregiver);
            $stmt_insert_caregiver->bindParam(':username', $username);
            $stmt_insert_caregiver->bindParam(':nip', $nip);
            $stmt_insert_caregiver->bindParam(':password', $password);
            $stmt_insert_caregiver->bindParam(':numer_telefonu', $numer_telefonu);
            $stmt_insert_caregiver->bindParam(':imie', $imie);
            $stmt_insert_caregiver->bindParam(':nazwisko', $nazwisko);
            $stmt_insert_caregiver->bindParam(':adres', $adres);
            // Pozostałe bindParam dla zmiennych formularza
            $stmt_insert_caregiver->execute();

            $caregiver_id = $pdo->lastInsertId();

            // Wstawienie zaznaczonych rolników do tabeli caregivers
            $query_insert_caregiver_farmers = "INSERT INTO caregivers (caregiver_id, farmer_id) VALUES (:caregiver_id, :farmer_id)";
            $stmt_insert_caregiver_farmers = $pdo->prepare($query_insert_caregiver_farmers);

            foreach ($selectedFarmers as $farmer_id) {
                $stmt_insert_caregiver_farmers->bindParam(':caregiver_id', $caregiver_id);
                $stmt_insert_caregiver_farmers->bindParam(':farmer_id', $farmer_id);
                $stmt_insert_caregiver_farmers->execute();
            }

            $pdo->commit();

            exit();
        } catch (PDOException $e) {
            $pdo->rollBack();
            echo "Błąd zapytania: " . $e->getMessage();
        }
        
    }
    ?>
</body>
</html>