
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,600,0,0" />

<link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
<link href="css/buttons.css" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">


<link href="css/sb-admin-2.css" rel="stylesheet">
<link href="css/footer.css" rel="stylesheet">
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
<title>Załączniki do upadków</title>
</head>
<body>
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">


<button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3" style="display: none;">
    <i class="fa fa-bars"></i>
</button>


<ul class="navbar-nav ml-auto">
    <li class="nav-item">
        <a href="logout.php" class="wyloguj">Wyloguj</a>
    </li>
    <li class="nav-item">
        <?php
        $ids = $_GET['ids'];
        $idf = $_GET['idf'];
        echo "<a href='informacje_padle.php?id=$ids&idf=$idf' class='powrot'>Powrót</a>";
        ?>
    </li>
</ul>
</nav>

<?php
echo "<div class='tytul'>";
echo "<a>Załączniki do upadków</a>";
echo "</div>";

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



$id_upadku = $_GET['id'];


$query = "SELECT * FROM pliki_upadki WHERE id_upadku = :id_upadku";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id_upadku', $id_upadku);
$stmt->execute();
$pliki_upadki = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($pliki_upadki) > 0) {

    echo "<div class='card shadow mb-4'>";
    echo "<div class='card-header py-3'>";
    echo "<h6 class='m-0 font-weight-bold text-primary'>Załączniki do upadku</h6>";
    echo "</div>";
    echo "<div class='card-body'>";
    echo "<div class='table-responsive'>";
    echo "<table class='table table-bordered' id='dataTable' width='100%' cellspacing='0'>";
    echo "<thead>";
    echo "<tr><th>Nazwa pliku</th><th>Link</th></tr>";
    echo "</thead>";
    echo "<tbody>";

    foreach ($pliki_upadki as $plik) {
        echo "<tr>";
        echo "<td>" . $plik['nazwa_pliku'] . "</td>";
        echo "<td><a href='upload/" . $plik['nazwa_pliku'] . "' target='_blank'>Otwórz</a></td>";
        echo "</tr>";
    }

    echo "</tbody>";
    echo "</table>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
} else {
    echo "Brak załączników dla tego upadku.";
}


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
</body>
</html>