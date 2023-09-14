<?php
session_start();


if (!isset($_SESSION['user_id'])) {
    
    header("Location: login.html");
    exit();
}


$user_id = $_SESSION['user_id'];


require_once('db_config.php');

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Nie można połączyć się z bazą danych: " . $e->getMessage());
}


$query = "SELECT * FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $query);


$row = mysqli_fetch_assoc($result);

if ($row['administrator'] == 'True') {
    header("Location: panel_administratora.php");
    exit();
} elseif ($row['administrator'] == 'boss') {
    header("Location: sadmin/panel_administratora2.php");
    exit();
} elseif ($row['administrator'] == 'barto') {
    header("Location: bartosz/panel_administratora.php");
    exit();
} else {


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="height=device-height, width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <link rel="stylesheet" href="index.css" />
    <link rel="stylesheet" href="index.css" />
    <link href="css/footer.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,600,0,0" />
    <title>Lista Ferm</title>
    <style>
        .data-container {
            top: 50px;
        }

        .informacje {
            text-align: center;
        }

        .floating-stack {
            top: 10px;
            width: 450px;
            background: rgba(255, 255, 255, .5);
            padding: 4rem;
            border-radius: 10px;
            position: relative;
            margin: 0 auto;
            text-align: center;
        }

        body {
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .container {
            flex: 1;
            position: relative;
        }
    </style>
</head>

<body>
    <div class='container'>
        <?php
        
        $email = $row['username'];
        $nip = $row['nip'];
        $numertel = $row['numer_telefonu'];
        $imie = $row['imie'];
        $nazwisko = $row['nazwisko'];
        $adres = $row['adres'];
        echo "<div class='informacje'>
                    <a>e-mail:&nbsp;$email&nbsp; NIP:&nbsp;$nip&nbsp; Numer telefonu:&nbsp;$numertel&nbsp; Imię:&nbsp;$imie&nbsp; Nazwisko:&nbsp;$nazwisko&nbsp; Adres fermy:&nbsp;$adres&nbsp;</a>
                </div>";
        ?>


        <div class='floating-stack'>
            <div class="login-card-logo">
                <img src="logo.png" alt="logo">
            </div>
            <div class='list-container'>
                <div class="scrollbar" id="scrollbar-1">
                    <div class="force-overflow"></div>
                    <dl>
                        <?php
                        
                        $query = "SELECT * FROM lista_ferm WHERE user_id = $user_id";
                        $result = mysqli_query($conn, $query);

                        
                        while ($row = mysqli_fetch_assoc($result)) {
                            $id_fermy = $row['id'];
                            $nazwa_fermy = $row['nazwa'];
                            echo "<dd><a href='informacje_fermy.php?id=$id_fermy'>$nazwa_fermy</a></dd>";
                        }
                        ?>
                    </dl>
                </div>
                <a href='stworz_ferme.php'>Dodaj fermę</a>
            </div>
            <a href='zglos_problem.php'>Zgłoś problem</a>
            <a href='logout.php'>Wyloguj</a>
        </div>
    </div>
    &nbsp;
    &nbsp;
    &nbsp;
    <div class="footer">
    <p>
        <span class="footer-text">&copy;</span>
        <span class="current-year">Year</span>
        <span class="footer-text">Hagric - Developed by <a href="https://www.ac-it.pl/" target="_blank" class="footer-link">AC IT Sp. z o.o.</a></span>
    </p>
</div>

    <script>
        const currentYear = new Date().getFullYear();
        document.querySelector(".current-year").textContent = currentYear;
    </script>
</body>
<?php
    exit();
                    }
?>
</html>
