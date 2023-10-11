<?php

$conn = mysqli_connect("mysql8", "37328198_fermy", "R&b^7C!pD*2@", "37328198_fermy");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['submit'])) {
    $data = $_POST['data'];
    $id_stada = $_POST['id_stada'];
    $preparat = $_POST['preparat'];
    $dawkowanie = $_POST['dawkowanie'];
    $okres = $_POST['okres'];
    $powod_podania = $_POST['opinia'];

    // Przygotowanie zapytania SQL z użyciem prepared statement
    $sql = "INSERT INTO zgloszenia_leczen (data, id_stada, preparat, dawkowanie, okres, powod_podania)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt === false) {
        die("Błąd przy przygotowywaniu zapytania: " . mysqli_error($conn));
    }

    // Bindowanie parametrów
    mysqli_stmt_bind_param($stmt, "sissss", $data, $id_stada, $preparat, $dawkowanie, $okres, $powod_podania);

    // Wykonanie zapytania
    if (mysqli_stmt_execute($stmt)) {
        echo "Dane zostały dodane do bazy danych.";
        header("Location: index.php");
    } else {
        echo "Błąd podczas dodawania danych do bazy danych: " . mysqli_error($conn);
    }

    // Zamykanie prepared statement
    mysqli_stmt_close($stmt);
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

                        
                        document.getElementById("data").value = dzisiejszaDataString;
                    </script>
                    <input type="hidden" name="id_stada" value="<?php echo $id_stada; ?>">

                    <label for="stado">Stado:</label>
                <div class="select-dropdown">
                    <?php
                    
if (isset($_GET['id'])) {
    $id_fermy = $_GET['id'];

    
    $sql = "SELECT * FROM stada WHERE id_farmy = '$id_fermy'";

    
    $result = mysqli_query($conn, $sql);

    
    echo "<select name='id_stada' id='stado' required>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<option value='" . $row['id'] . "'>" . $row['numer_stada'] . "</option>";
    }
    echo "</select>";

    
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

                    <label for="powod_podania">Powód podania leku (opcjonalnie):</label>
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
