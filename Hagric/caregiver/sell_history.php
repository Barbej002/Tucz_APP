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

// Pobieranie danych o sprzedażach ze wszystkich ferm
$query = "SELECT s.id AS id_stada, s.numer_stada, sprzedane.date, sprzedane.nazwa_ubojni, sprzedane.waga, sprzedane.liczba_sztuk
    FROM stada AS s
    INNER JOIN sprzedane ON s.id = sprzedane.id_stada";
$stmt = $pdo->prepare($query);
$stmt->execute();
$sprzedane_dane = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">

<title>Historia sprzedaż</title>


<link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
<link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">


<link href="css/sb-admin-2.css" rel="stylesheet">


<link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
<link href="css/buttons.css" rel="stylesheet">
<link href="css/footer.css" rel="stylesheet">
<link href="css/navbar.css" rel="stylesheet">

<style>
    #content-wrapper {
    margin-top: 20px; 
    margin-bottom: 60px; 
    padding: 20px; 
}

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

        tr.odd {
    background-color: rgba(255, 255, 255, 0); 
}


tr.even {
    background-color: rgba(200, 200, 200, .4); 
}

        .powrot {
            display: inline-block;
            font-size: 20px;
        }

        .informacje {
            padding-top: 60px;
        }

        .footer {
    position: absolute;
    bottom: 0;
    width: 100%;
    background-color: #222222;
    margin-top: 20px; 
}
                .footer-text {
            color: #fff;
        }

        .footer-link {
            color: #fff;
            font-weight: bold;
            text-decoration: none;
        }

        .kontener {
            padding-bottom: 30px;
        }

        .tytul {
            text-align: center;
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 25px;
        }

        .card {
            margin-top: 0;
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
            margin-bottom: 10px; 
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
</head>
<body id="page-top">
<nav class="navbar">
<div class="navbar-buttons">
<a href="lista_rolnikow.php" class="navbar-button">Powrót</a>
</div>
<a href="#" class="navbar-logo">Historia sprzedaży</a>
<div class="navbar-buttons">
            <a href="/Hagric/logout.php" class="navbar-button">Wyloguj</a>
        </div>
    </nav>


    <div id="wrapper">
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <div class="container-fluid">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Historia sprzedaży ze wszystkich ferm</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Numer stada</th>
                                    <th>Data</th>
                                    <th>Nazwa ubojni</th>
                                    <th>Waga</th>
                                    <th>Ilość sprzedanych sztuk</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (count($sprzedane_dane) > 0) {
                                    foreach ($sprzedane_dane as $dane) {
                                        echo "<tr>";
                                        echo "<td>" . $dane['numer_stada'] . "</td>";
                                        echo "<td>" . $dane['date'] . "</td>";
                                        echo "<td>" . $dane['nazwa_ubojni'] . "</td>";
                                        echo "<td>" . $dane['waga'] . "</td>";
                                        echo "<td>" . $dane['liczba_sztuk'] . "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='5'>Brak informacji o sprzedażach ze wszystkich ferm.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
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


<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>


<script src="vendor/jquery-easing/jquery.easing.min.js"></script>


<script src="js/sb-admin-2.min.js"></script>


<script src="vendor/datatables/jquery.dataTables.js"></script>
<script src="vendor/datatables/dataTables.bootstrap4.js"></script>


<script src="js/demo/datatables-demo.js"></script>
</body>
</html>