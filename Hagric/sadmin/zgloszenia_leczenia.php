<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,600,0,0" />
    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="css/buttons.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.css" rel="stylesheet">
    <link href="css/footer.css" rel="stylesheet">
    <link href="css/navbar.css" rel="stylesheet">

    <style>
        body {
            overflow-x: hidden;
            background-image: url(background.jpg);
            background-size: 100%;
            background-repeat: no-repeat;
        }

        .topbar {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 9999;
            background-color: #ffffff00 !important;
        }

        .wyloguj {
            display: inline-block;
            margin-right: 2px;
            font-size: 20px;
        }

        .powrot {
            display: inline-block;
            font-size: 20px;
        }

        .informacje {
            padding-top: 60px;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: #222222;
        }

        .kontener {
            padding-bottom: 30px;
        }

        .tytul {
            text-align: center;
            margin-top: 20px;
            margin-bottom: 20px;
            font-size: 25px;
        }

        .card {
            margin-top: 20px;
        }

        .card-header {
            background-color: #cd000000;
            color: #fff;
        }

        .card-body {
            padding: 0;
        }

        .table {
            margin-bottom: 0;
        }

        .table th,
        .table td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #cd000000;
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #ffffff00;
        }

        .upadek {
            display: block;
            width: 100%;
            text-align: center;
            padding: 10px 0;
            font-size: 16px;
            font-weight: bold;
            color: #ff9900;
            background-color: #cd000000;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .footer-text {
            color: #fff;
        }

        .footer-link {
            color: #fff;
            font-weight: bold;
            text-decoration: none;
        }

        .no-data {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin-top: 30px;
        }

    </style>
    <title>Zgłoszenia problemów</title>
</head>

<body>
<nav class="navbar">
<div class="navbar-buttons">
<a href="index.php" class="navbar-button">Powrót</a>
</div>
<a href="#" class="navbar-logo">Informacje o upadkach</a>
<div class="navbar-buttons">
            <a href="logout.php" class="navbar-button">Wyloguj</a>
        </div>
    </nav>

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

// Pobranie zgłoszeń leczeń wraz z informacjami o stadach
$query = "SELECT zgloszenia_leczen.*, stada.numer_stada, stada.id_farmy
FROM zgloszenia_leczen
JOIN stada ON zgloszenia_leczen.id_stada = stada.id";
$stmt = $pdo->query($query);
$zgloszenia = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($zgloszenia) > 0) {
    // Wyświetlanie tabeli z zgłoszeniami leczeń
    echo "<div class='card shadow mb-4'>";
    echo "<div class='card-header py-3'>";
    echo "<h6 class='m-0 font-weight-bold text-primary'>Zgłoszenia leczeń</h6>";
    echo "</div>";
    echo "<div class='card-body'>";
    echo "<div class='table-responsive'>";
    echo "<table class='table table-bordered' id='dataTable' width='100%' cellspacing='0'>";
    echo "<thead>";
    echo "<tr>";
    echo "<th>Rolnik</th>";
    echo "<th>Stado</th>";
    echo "<th>Data</th>";
    echo "<th>Preparat</th>";
    echo "<th>Dawkowanie</th>";
    echo "<th>Okres</th>";
    echo "<th>Opinia</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";

    foreach ($zgloszenia as $zgloszenie) {
        $idz = $zgloszenie['id'];
        $stado = $zgloszenie['numer_stada'];
        $data = $zgloszenie['data'];
        $preparat = $zgloszenie['preparat'];
        $dawkowanie = $zgloszenie['dawkowanie'];
        $okres = $zgloszenie['okres'];
        $opis = $zgloszenie['opinia'];
        $id_fermy = $zgloszenie['id_farmy']; // Pobieramy id fermy

        // Pobieramy właściciela fermy na podstawie id_fermy z tabeli lista_ferm
        $query2 = "SELECT wlasciciel FROM lista_ferm WHERE id = '$id_fermy'";
        $stmt2 = $pdo->query($query2);
        $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
        $wlasciciel = $row2['wlasciciel'];

        // Wyświetlamy dane w tabeli
        echo "<tr>";
        echo "<td>$wlasciciel</td>"; // Wyświetlamy właściciela fermy
        echo "<td>$stado</td>";
        echo "<td>$data</td>";
        echo "<td>$preparat</td>";
        echo "<td>$dawkowanie</td>";
        echo "<td>$okres</td>";
        echo "<td>$opis</td>";
        echo "</tr>";
    }

    echo "</tbody>";
    echo "</table>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
} else {
    echo "<div class='no-data'>Brak zgłoszeń</div>";
}

$pdo = null; // Zamknięcie połączenia z bazą danych
?>

    <div class="kontener">
        <div class="footer">
            <p><span class="footer-text">&copy;</span> <span class="current-year">Year</span> <span class="footer-text">Hagric - Developed by <a href="https://www.ac-it.pl/" target="_blank" class="footer-link">AC IT Sp. z o.o.</a></span></p>
        </div>
    </div>

    <script>
        const currentYear = new Date().getFullYear();
        document.querySelector(".current-year").textContent = currentYear;
    </script>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>
