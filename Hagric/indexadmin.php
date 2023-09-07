<?php
session_start();

// Sprawdzenie czy użytkownik jest zalogowany
if (!isset($_SESSION['user_id'])) {
    // Przekierowanie na stronę logowania
    header("Location: login.html");
    exit();
}

// Pobranie ID zalogowanego użytkownika
$user_id = $_SESSION['user_id'];

// Połączenie z bazą danych
$conn = mysqli_connect("mysql8", "37328198_fermy", "R&b^7C!pD*2@", "37328198_fermy");

// Pobranie informacji o użytkowniku
$query = "SELECT * FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $query);

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
        // Wyświetlenie informacji o użytkowniku
        $email = $row['username'];
        $nip = $row['nip'];
        $numertel = $row['numer_telefonu'];
        $imie = $row['imie'];
        $nazwisko = $row['nazwisko'];
        $adres = $row['adres'];
        ?>


        <div class='floating-stack'>
            <div class="login-card-logo">
                <img src="logo.png" alt="logo">
            </div>
            <div class="list-container">
    <div class="scrollbar" id="scrollbar-1">
        <div class="force-overflow">
            <dl>
                <?php
                if (isset($_GET['id'])) {
                    $user_id = $_GET['id'];

                    // Pobranie informacji o fermach przypisanych do danego użytkownika
                    $query = "SELECT * FROM lista_ferm WHERE user_id = $user_id";
                    $result = mysqli_query($conn, $query);

                    // Wyświetlenie informacji o fermach użytkownika
                    while ($row = mysqli_fetch_assoc($result)) {
                        $id_fermy = $row['id'];
                        $nazwa_fermy = $row['nazwa'];
                        echo "<dd><a href='informacje_fermy.php?id=$id_fermy'>$nazwa_fermy</a></dd>";
                    }
                } else {
                    echo "Brak przekazanego ID użytkownika.";
                }
                ?>
                    </dl>
                </div>
            </div>
            <a href='index.php'>Powrót</a>
        </div>
        <a href='logout.php'>Wyloguj</a>
    </div>
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
