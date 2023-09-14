<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = $_POST['title'];
    $opis = $_POST['opis'];


    $nazwy_plikow = [];
    $tmp_pliki = [];

    if (!empty($_FILES['plik']['name'][0])) {
        $ile_plikow = count($_FILES['plik']['name']);
        for ($i = 0; $i < $ile_plikow; $i++) {
            $nazwa_pliku = $_FILES['plik']['name'][$i];
            $tmp_plik = $_FILES['plik']['tmp_name'][$i];
            $nazwy_plikow[] = $nazwa_pliku;
            $tmp_pliki[] = $tmp_plik;
        }
    }


    require_once('db_config.php');

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Nie można połączyć się z bazą danych: " . $e->getMessage());
    }
    


    if (!isset($_SESSION['user_id'])) {

        header("Location: login.html");
        exit();
    }


    $user_id = $_SESSION['user_id'];


    $query = "SELECT username FROM users WHERE id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $username = $result['username'];


    $query = "INSERT INTO zgloszenia_problemow (username, title, opis, nazwa_pliku) VALUES (:username, :title, :opis, :nazwa_pliku)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':opis', $opis);
    $stmt->bindParam(':nazwa_pliku', $nazwa_plikow_string);


    $nazwa_plikow_string = implode(',', $nazwy_plikow);

    $stmt->execute();


    if (!empty($nazwy_plikow)) {
        $katalog_docelowy = "upload/"; 
        $ile_plikow = count($nazwy_plikow);
        for ($i = 0; $i < $ile_plikow; $i++) {
            $nazwa_pliku = $nazwy_plikow[$i];
            $tmp_plik = $tmp_pliki[$i];
            move_uploaded_file($tmp_plik, $katalog_docelowy . $nazwa_pliku);
        }
    }


    header("Location: index.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,600,0,0" />

<link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">


<link href="css/sb-admin-2.css" rel="stylesheet">
<link href="css/footer.css" rel="stylesheet">
<link href="css/navbar.css" rel="stylesheet">
<style>

    h1 {
        transform: translateX(40%);
    }

    body {
        overflow-x: hidden;
        background-image: url(background.jpg);
        background-size: 100%;
        background-repeat: no-repeat;
    }

    .card {
        margin: 20px 0;
        border-radius: 8px;
    }

    .card-header {
        background-color: #ffffff;
        color: #fff;
    }

    .card-body {
        background-color: #fff;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        font-weight: bold;
    }

    .form-control {
        width: 100%;
        padding: 10px;
        border-radius: 4px;
        border: 1px solid #ddd;
    }

    .form-control:focus {
        outline: none;
        box-shadow: 0 0 0 2px #ffa000;
    }

    .kontener {
        position: fixed;
        bottom: 0;
        width: 100%;
        background-color: #f8f9fc00;
        padding: 0px 0;
        text-align: center;
    }

    .footer {
        margin-top: 10px;
        font-size: 14px;
        color: #5a5c69;
    }

    .footer-text {
        display: inline-block;
        vertical-align: middle;
    }

    .footer-link {
        color: #5a5c69;
        text-decoration: none;
    }

    .footer-link:hover {
        text-decoration: underline;
    }

    .current-year {
        font-weight: bold;
    }

</style>
<title>Zgłaszanie problemu</title>
</head>
<body>
<nav class="navbar">
<div class="navbar-buttons">
        <a href='index.php' class='navbar-button'>Powrót</a>
</div>
<a href="#" class="navbar-logo">Zgłoś problem</a>
<div class="navbar-buttons">
            <a href="logout.php" class="navbar-button">Wyloguj</a>
        </div>
    </nav>
    <div class="card shadow">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Formularz zgłaszania problemu</h6>
    </div>
    <div class="card-body">
        <form method="post" action="zglos_problem.php" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Tytuł:</label>
                <input type="text" id="title" name="title" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="opis">Opis problemu:</label>
                <textarea id="opis" name="opis" class="form-control" rows="5" required></textarea>
            </div>
            <div class="form-group">
                <label for="plik">Załącz pliki (jeśli dotyczy):</label>
                <input type="file" id="plik" name="plik[]" class="form-control" multiple>
            </div>
            <button type="submit" class="btn btn-primary">Wyślij zgłoszenie</button>
        </form>
    </div>
    </div>
    <div class="kontener">
    <div class="footer">
        <p><span class="footer-text">&copy;</span> <span class="current-year">Year</span> <span class="footer-text">Hagric - Developed by <a href="https://www.ac-it.pl/" target="_blank" class="footer-link">AC IT Sp. z o.o.</a></span></p>
    </div>
</div>
</div>
<script>
    const currentYear = new Date().getFullYear();
    document.querySelector(".current-year").textContent = currentYear;
</script>
</body>
</html>
