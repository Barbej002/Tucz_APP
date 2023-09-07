<?php
session_start();

// Sprawdzenie czy użytkownik jest zalogowany
if (!isset($_SESSION['user_id'])) {
    // Przekierowanie na stronę logowania
    header("Location: login.html");
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
    <title>Edytuj stado</title>
        <!-- Custom fonts for this template-->
        <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="css/buttons.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.css" rel="stylesheet">
    <link href="css/footer.css" rel="stylesheet">

    

    

    <style>
        h1 {
            transform: translateX(50%);
        }
        body {
            overflow-x: hidden;
            background-image: url(background.jpg);
            background-size: 100%;
            background-repeat: no-repeat;
            background-color: #fcddbe;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: #222222;
        }

        .accordion {
        border: none;
        margin-bottom: 10px;
        text-align: center;
        margin: 0 auto;
        border-radius: 20px; /* Dodajemy zaokrąglenie rogów */
    }

    .accordion-header {
        background-color: #ffffff;
        padding: 10px;
        cursor: pointer;
        border-radius: 5px; /* Dodajemy zaokrąglenie rogów */
        color: #ff9900;
    }

    .accordion-content {
        padding: 10px;
        display: none;
    }

    .content {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: calc(0,5vh - 20px); /* Ustal wysokość tak, aby akordeon nie nachodził na stopkę */
    }
        /* Navbar styles */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #f8f9fa;
            padding: 10px;
        }

        .navbar-logo {
            font-weight: bold;
            font-size: 35px;
            text-decoration: none !important;
            cursor: default;
        }
        .navbar-logo:hover::after {
            text-decoration: none;
            background-color: transparent
        }

        .navbar-buttons {
            display: flex;
            align-items: center;
            justify-content: flex-start; /* Przesunięcie przycisku "Powrót" na lewo */
        }

        .navbar-buttons a {
            margin-left: 10px;
            text-decoration: none;
            color: #000;
        }
        /* Styl dla nieparzystych wierszy */
tr.odd {
    background-color: rgba(255, 255, 255, 0); /* Tutaj możesz ustawić kolor dla nieparzystych wierszy */
}

/* Styl dla parzystych wierszy */
tr.even {
    background-color: rgba(200, 200, 200, .4); /* Tutaj możesz ustawić kolor dla parzystych wierszy */
}

        /* Responsive styles */
        @media (max-width: 768px) {
            .navbar-buttons {
                justify-content: flex-end; /* Przesunięcie przycisków na prawo w wersji responsywnej */
            }

            .navbar-buttons a {
                margin-left: 0;
                margin-right: 10px; /* Dodanie marginesu po prawej stronie przycisków */
            }
        }
    </style>

</head>
<body>
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">
            <nav class="navbar">
        <div class="navbar-buttons">
            <a href="panel_administratora2.php" class="navbar-button">Powrót</a>
        </div>
        <a href="#" class="navbar-logo"><?php

$conn = mysqli_connect("mysql8", "37328198_fermy", "R&b^7C!pD*2@", "37328198_fermy");

$id = $_GET['id'];
$idf = $_GET['id']; 
$query = "SELECT * FROM lista_ferm WHERE id=$id";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
echo $row['nazwa'];?></a>
        <div class="navbar-buttons">
            <a href="/logout.php" class="navbar-button">Wyloguj</a>
        </div>
    </nav>




<?php

$conn = mysqli_connect("mysql8", "37328198_fermy", "R&b^7C!pD*2@", "37328198_fermy");

$id = $_GET['id'];
$idf = $_GET['id'];



$query = "SELECT * FROM lista_ferm WHERE id=$id";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

$query = "SELECT * FROM stada WHERE id_farmy=$id";
$result = mysqli_query($conn, $query);
$stada = mysqli_fetch_all($result, MYSQLI_ASSOC);

$query = "SELECT * FROM pasza WHERE id_fermy=$id";
$result = mysqli_query($conn, $query);
$pasze = mysqli_fetch_all($result, MYSQLI_ASSOC);

echo "&nbsp;";

echo "
<div class='card shadow mb-4'>
<div class='card-header py-3'>
    <h6 class='m-0 font-weight-bold text-primary'>Informacje o stadach</h6>
</div>";
echo "
<div class='card-body'>
<div class='table-responsive'>
    <table class='table table-bordered' id='dataTable' width='100%' cellspacing='0'>
        <thead>";
        echo "<tr><th>Numer stada</th><th>Opis/komora</th><th>Ilość sztuk</th><th>Ilość padłych sztuk</th><th>Procent upadków</th><th>Stan na dzień: " . date('d-m-Y') . "</th><th>Dzień tuczu</th><th>Informacje o upadkach</th></tr>";
echo "</thead>"; 
echo "<tbody>";
$counter = 0;
foreach ($stada as $stado) {
    $id_stada = $stado['id'];
    $numer_stada = $stado['numer_stada'];
    $opis = $stado['opis'];
    $ilosc_sztuk = $stado['ilosc_sztuk'];
        // Sprawdzenie, czy wiersz jest parzysty czy nieparzysty
        $row_class = ($counter % 2 === 0) ? 'even' : 'odd';
        $counter++;

    // Aktualizacja informacji o upadkach
    $query = "SELECT SUM(ilosc_padlych) AS suma_padlych FROM informacje_upadki WHERE id_stada=$id_stada";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $suma_padlych = $row['suma_padlych'];

    $query = "UPDATE stada SET ilosc_padlych='$suma_padlych' WHERE id='$id_stada'";
    mysqli_query($conn, $query);
    

    // Pobranie zaktualizowanych danych
    $query = "SELECT * FROM stada WHERE id=$id_stada";
    $result = mysqli_query($conn, $query);
    $stado = mysqli_fetch_assoc($result);

    $ilosc_padlych = $stado['ilosc_padlych'];
    $przyczyna = $stado['przyczyna'];
    $opinia = $stado['opinia'];
    $procent = $stado['procent'];
    $procent_padlych = round(($ilosc_padlych / $ilosc_sztuk) * 100, 1);
    $dzien_tuczu = $stado['dzien_tuczu'];

    if ($ilosc_padlych / $ilosc_sztuk >= 0.02) {
        echo "<tr style='background-color: #ffc7c7cc;'>";
    } else {
        echo "<tr class='$row_class'>";
    }

    echo "<td>$numer_stada</td><td>$opis</td><td>$ilosc_sztuk</td><td>$ilosc_padlych</td><td>$procent_padlych%</td><td>" . ($ilosc_sztuk - $ilosc_padlych) . "</td><td>$dzien_tuczu</td><td><a href='informacje_padle.php?id=$id_stada&idf=$idf'>informacje o padłych</a></td>";
    

    echo "</tr>";
}
echo "</tbody>";
echo "</table>";
echo "</div>";

echo "
<div class='card shadow mb-4'>
<div class='card-header py-3'>
    <h6 class='m-0 font-weight-bold text-primary'>Informacje o paszy</h6>
</div>";
echo "
<div class='card-body'>
<div class='table-responsive'>
    <table class='table table-bordered' id='dataTable' width='100%' cellspacing='0'>
        <thead>";
echo "<tr><th>Nazwa paszy</th><th>Ilość paszy</th></tr>";
echo "</thead>";
echo "<tfoot>";
echo "<tr>";
echo "</tr>";
echo "</tfoot>";
echo "<tbody>";
$counter = 0;
foreach ($pasze as $pasza) {
    $nazwa_paszy = $pasza['nazwa_paszy'];
    $ilosc_paszy = $pasza['ilosc_paszy'];
        // Sprawdzenie, czy wiersz jest parzysty czy nieparzysty
        $row_class = ($counter % 2 === 0) ? 'even' : 'odd';
        $counter++;
    echo "<tr class='$row_class'>";
    
    echo "<td>$nazwa_paszy</td><td>$ilosc_paszy Kg</td>";
    echo "</tr>";
}
echo "</tbody>";
echo "</table>";
echo "</div>";
?>

</div>
</div>
<div class="content">
    <div class="info">
        <?php
$conn = mysqli_connect("mysql8", "37328198_fermy", "R&b^7C!pD*2@", "37328198_fermy");
        $id = $_GET['id'];
        $idf = $_GET['id'];

        // Pobranie informacji na temat ferm
        $query = "SELECT * FROM lista_ferm WHERE id=$id";
        $result = mysqli_query($conn, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            $nazwa = $row['nazwa'];
            $wlasciciel = $row['wlasciciel'];
            $adres = $row['adres'];
            $kontakt = $row['kontakt'];
            $numer_s = $row['numer_s'];
            $przedstawiciel_hagric = $row['przedstawiciel_hagric'];
            $dostawca_pasz = $row['dostawca_pasz'];
            $przedstawiciel_dostawcy_pasz = $row['przedstawiciel_dostawcy_pasz'];
            $lekarz = $row['lekarz_prowadzacy']
            ?>

            <div class="accordion">
                <div class="accordion-header">
                    Informacje o fermie
                </div>
                <div class="accordion-content">
                    <strong>Nazwa:&nbsp;</strong> <?php echo $nazwa; ?><br>
                    <strong>Właściciel:&nbsp;</strong> <?php echo $wlasciciel; ?><br>
                    <strong>Adres:&nbsp;</strong> <?php echo $adres; ?><br>
                    <strong>Kontakt:&nbsp;</strong> <?php echo $kontakt; ?><br>
                    <strong>Numer siedziby stada:&nbsp;</strong> <?php echo $numer_s; ?><br>
                    <strong>Przedstawiciel Hagric:&nbsp;</strong> <?php echo $przedstawiciel_hagric; ?><br>
                    <strong>Dostawca pasz:&nbsp;</strong> <?php echo $dostawca_pasz; ?><br>
                    <strong>Przedstawiciel dostawcy pasz:&nbsp;</strong> <?php echo $przedstawiciel_dostawcy_pasz; ?><br>
                    <strong>Lekarz prowadzący:&nbsp;</strong> <?php echo $lekarz; ?><br>
                </div>
            </div>
        <?php
        }
        ?>
    </div>
</div>

</div>
<div class="footer">
    <p>
        <span class="footer-text">&copy;</span>
        <span class="current-year">Year</span>
        <span class="footer-text">Hagric - Developed by <a href="https://www.ac-it.pl/" target="_blank" class="footer-link">AC IT Sp. z o.o.</a></span>
    </p>
</div>

<script>
    const currentYear = new Date().getFullYear();
    document.querySelector('.current-year').textContent = currentYear;

    var navItems = document.getElementsByClassName('nav-item');
    for (var i = 0; i < navItems.length; i++) {
        var navItem = navItems[i];
        navItem.style.listStyleType = 'none';
    }

    var accordions = document.getElementsByClassName('accordion');
    for (var i = 0; i < accordions.length; i++) {
        var accordion = accordions[i];
        var header = accordion.getElementsByClassName('accordion-header')[0];
        var content = accordion.getElementsByClassName('accordion-content')[0];

        header.addEventListener('click', function () {
            this.classList.toggle('active');
            if (content.style.display === 'block') {
                content.style.display = 'none';
            } else {
                content.style.display = 'block';
            }
        });
    }
</script>
</body>
</html>

