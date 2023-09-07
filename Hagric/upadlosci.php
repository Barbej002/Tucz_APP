<?php
// Sprawdzenie czy użytkownik jest zalogowany
session_start();
if (!isset($_SESSION['user_id'])) {
    // Przekierowanie na stronę logowania
    header("Location: login.html");
    exit();
}

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

// Pobranie informacji o upadkach ze wszystkich stad
$query = "SELECT * FROM informacje_upadki";
$stmt = $pdo->prepare($query);
$stmt->execute();
$informacje_upadki = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="pl">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">

<title>Informacje o upadkach</title>

<!-- Niestandardowe czcionki dla tego szablonu -->
<link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
<link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

<!-- Niestandardowe style dla tego szablonu -->
<link href="css/sb-admin-2.css" rel="stylesheet">

<!-- Niestandardowe style dla tej strony -->
<link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
<link href="css/buttons.css" rel="stylesheet">
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

        /* Styl dla urządzeń mobilnych */
        @media (max-width: 896px) {
            h1 {
                transform: none;
            }
            .topbar {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 9999;
            background-color: white !important; 

        }

        .informacje {
            padding-top: 60px;
        }

        .info {
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
        }

        .footer {
            background-color: #222222;
            padding: 10px 0;
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            text-align: center;
            z-index: 2;
        }

        .content {
            margin-bottom: 40px; /* Wysokość stopki */
        }

        .kontener {
            padding-bottom: 30px;
        }

        .wyloguj {
            display: inline-block;
            margin-right: 2px;
            left: 80%;
            font-size: 20px;
        }

        .powrot {
            display: inline-block;
            font-size: 20px;
            right: 480%;
        }
        }

        /* Styl dla urządzeń o szerokości od 768px (np. tablety i desktopy) */
        @media (min-width: 768px) {
            
            .topbar {
                position: fixed;
                top: 0;
                width: 100%;
                z-index: 9999;
                background-color: #ffffff00;
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
            .wyloguj {
            display: inline-block;
            margin-right: 2px;
            left: 4rem;
            font-size: 20px;
        }

            .powrot {
            display: inline-block;
            right: 114rem;
            font-size: 20px;
        }
        }

    </style>
</head>
<body id="page-top">
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

<!-- Sidebar Toggle (Topbar) -->
<button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3" style="display: none;">
    <i class="fa fa-bars"></i>
</button>

<!-- Nav Item - User Information -->
<ul class="navbar-nav ml-auto">
    <li class="nav-item">
        <a href="logout.php" class="wyloguj">Wyloguj</a>
    </li>
    <li class="nav-item">
        <a href="index.php" class="powrot">Powrót</a>
        </li>
    </ul>
</nav>
    <div class='tytul'>
        <a>Informacje o rolnikach</a>
    </div>
    <!-- Zawartość strony -->
<div id="wrapper">
    

<!-- Kontener zawartości -->
<div id="content-wrapper" class="d-flex flex-column">

    <!-- Główna zawartość -->
    <div id="content">

        <!-- Początek zawartości strony -->
        <div class="container-fluid">
            

            

            <!-- Przykład tabeli -->

                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Lista rolników</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                                        <tr>
                                            <th>ID stada</th>
                                            <th>Ilość padłych</th>
                                            <th>Przyczyna</th>
                                            <th>Opiniujący</th>
                                            <th>Załączniki</th>
                                            <th>Właściciel fermy</th>
                                        </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                            <th>ID stada</th>
                                            <th>Ilość padłych</th>
                                            <th>Przyczyna</th>
                                            <th>Opiniujący</th>
                                            <th>Załączniki</th>
                                            <th>Właściciel fermy</th>
                                        </tr>
                            </tfoot>
                            <tbody>
<?php
    if (count($informacje_upadki) > 0) {
        // Wyświetlanie tabeli z informacjami o upadkach
        foreach ($informacje_upadki as $informacja) {
            echo "<tr>";
            echo "<td>" . $informacja['id_stada'] . "</td>";
            $ids = $informacja['id_stada'];
            echo "<td>" . $informacja['ilosc_padlych'] . "</td>";
            echo "<td>" . $informacja['przyczyna'] . "</td>";
            echo "<td>" . $informacja['opiniujacy'] . "</td>";
            $id_upadku = $informacja['id'];

            // Pobranie informacji o załącznikach dla danego upadku
            $query = "SELECT * FROM pliki_upadki WHERE id_upadku = :id_upadku";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':id_upadku', $id_upadku);
            $stmt->execute();
            $pliki_upadki = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo "<td>";
            if (count($pliki_upadki) > 0) {
                $sciezka_pliku = "upload/" . $pliki_upadki[0]['nazwa_pliku']; // Wybieramy pierwszy załącznik
                echo "<a href='zalaczniki_upadki2.php?id=" . $pliki_upadki[0]['id_upadku'] . "&ids=$ids' target='_blank'>Zobacz załączniki</a><br>";
            }
            echo "</td>";

            // Pobranie informacji o stanie przypisanym do upadku
            $query = "SELECT * FROM stada WHERE id = :id_stada";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':id_stada', $informacja['id_stada']);
            $stmt->execute();
            $stado = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($stado) {
                // Pobranie informacji o fermy przypisanej do stada
                $query = "SELECT * FROM lista_ferm WHERE id = :id_farmy";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':id_farmy', $stado['id_farmy']);
                $stmt->execute();
                $lista_ferm = $stmt->fetch(PDO::FETCH_ASSOC);
                $id_fermy = ['id_farmy'];

                if ($lista_ferm) {
                    echo "<td>" . $lista_ferm['wlasciciel'] . "</td>";
                } else {
                    echo "<td>-</td>";
                }
            } else {
                echo "<td>-</td>";
            }

            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
    } else {
        echo "Brak informacji o upadkach.";
    }
    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "</div>";

    ?>
    <!-- Przycisk przewijania do góry -->
<a class="scroll-to-top rounded" href="#page-top">
<i class="fas fa-angle-up"></i>
</a>

<div class="kontener">
            <div class="footer">
                <p><span class="footer-text">&copy;</span> <span class="current-year">Year</span> <span class="footer-text">Hagric - Developed by <a href="https://www.ac-it.pl/" target="_blank" class="footer-link">AC IT Sp. z o.o.</a></span></p>
            </div>
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

<!-- Skrypty podstawowe Bootstrapa -->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Wtyczki skryptów podstawowych -->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Niestandardowe skrypty dla wszystkich stron -->
<script src="js/sb-admin-2.min.js"></script>

<!-- Wtyczki skryptów dla tej strony -->
<script src="vendor/datatables/jquery.dataTables.js"></script>
<script src="vendor/datatables/dataTables.bootstrap4.js"></script>

<!-- Niestandardowe skrypty dla tej strony -->
<script src="js/demo/datatables-demo.js"></script>
</body>
</html>