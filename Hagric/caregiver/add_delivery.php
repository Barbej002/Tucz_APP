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
    $ilosc_sztuk = $_POST['liczba_sztuk'];
    $data = $_POST['data'];

    // Add a record to the 'deliveries' table
    $query = "INSERT INTO deliveries (farm_id, herd_id, quantity, date) VALUES (:farm_id, :herd_id, :quantity, :date)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':farm_id', $idf);
    $stmt->bindParam(':herd_id', $id_stada);
    $stmt->bindParam(':quantity', $ilosc_sztuk);
    $stmt->bindParam(':date', $data);
    $stmt->execute();

    $last_insert_id = $pdo->lastInsertId(); // Retrieve the last inserted ID

    // Fetch the 'id' from the GET request
    if (isset($_GET['id'])) {
        $id_stada = $_GET['id'];

        // Update the number of pieces in the 'stada' table
        $query = "UPDATE stada SET ilosc_sztuk = ilosc_sztuk + :ilosc_sztuk WHERE id = :id_stada";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id_stada', $id_stada);
        $stmt->bindParam(':ilosc_sztuk', $ilosc_sztuk);
        $stmt->execute();

        header("Location: informacje_fermy.php?id=$idf");
        exit();
    }
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
    <title>Zgłoś dostawę</title>
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

    </script>
</head>

<body>
    <div class="login-card-container">
        <div class="login-card">
            <div class="login-card-header">
                <h1>Zgłoś dostawę</h1>
            </div>
            <a href='informacje_fermy.php?id=<?php echo $idf; ?>' class='powrot'; style='color: black;'>Powrót</a>
            <a href='logout.php' class='wyloguj'; style='color: black;'>Wyloguj</a>

            <form class="login-card-form" method="POST" enctype="multipart/form-data">
                <div class="form-item">
                    <input type="hidden" name="id_stada" value="<?php echo $id_stada; ?>">

                    <label for="liczba_sztuk">Liczba sztuk:</label>
                    <input type="number" name="liczba_sztuk" id="liczba_sztuk" required>
                    <label for="data">Data:</label>
                    <div class="in">
                        <input type="date" name="data" id="data" required>
                    </div>

                    <button type="submit" name="submit">Dodaj dostawę</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
