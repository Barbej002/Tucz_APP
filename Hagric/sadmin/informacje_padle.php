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
    <link href="css/navbar.css" rel="stylesheet">

    <style>
        body {
            overflow-x: hidden;
            background-image: url(background.jpg);
            background-size: 100%;
            background-repeat: no-repeat;
            background-color: #fcddbe;
        }
        tr.odd {
    background-color: rgba(255, 255, 255, 0); 
}


tr.even {
    background-color: rgba(200, 200, 200, .4); 
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
    <title>Informacje o upadkach stada</title>
</head>

<body>
<div id="content-wrapper" class="d-flex flex-column">


<div id="content">
<nav class="navbar">
<div class="navbar-buttons">
            <?php
                $idf = $_GET['idf'];
                echo "<a href='informacje_fermy.php?id=$idf' class='navbar-button'>Powrót</a>";
                ?>
</div>
<a href="#" class="navbar-logo">Informacje o upadkach</a>
<div class="navbar-buttons">
            <a href="/Hagric/logout.php" class="navbar-button">Wyloguj</a>
        </div>
    </nav>


    
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
    
        $idf = $_GET['idf'];
    
        
        if (isset($_GET['id'])) {
            $id_stada = $_GET['id'];
    
            
            $query = "SELECT * FROM informacje_upadki WHERE id_stada = :id_stada";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':id_stada', $id_stada);
            $stmt->execute();
            $informacje_upadki = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            if (count($informacje_upadki) > 0) {
                
                echo "<div class='card shadow mb-4'>";
                echo "<div class='card-header py-3'>";
                echo "<h6 class='m-0 font-weight-bold text-primary'>Informacje o stadach</h6>";
                echo "</div>";
                echo "<div class='card-body'>";
                echo "<div class='table-responsive'>";
                echo "<table class='table table-bordered' id='dataTable' width='100%' cellspacing='0'>";
                echo "<thead>";
                echo "<tr><th>ID Stada</th><th>Ilość Padłych</th><th>Przyczyna</th><th>Opiniujący</th><th>Załączniki</th></tr>";
                echo "</thead>";
                echo "<tbody>";
    
                $counter = 0;
                foreach ($informacje_upadki as $informacja) {
                            
        $row_class = ($counter % 2 === 0) ? 'even' : 'odd';
        $counter++;
                    echo "<tr class='$row_class'>";
                    echo "<td>" . $informacja['id_stada'] . "</td>";
                    echo "<td>" . $informacja['ilosc_padlych'] . "</td>";
                    echo "<td>" . $informacja['przyczyna'] . "</td>";
                    echo "<td>" . $informacja['opiniujacy'] . "</td>";
    
                    $id_upadku = $informacja['id'];
    
                    
                    $query = "SELECT * FROM pliki_upadki WHERE id_upadku = :id_upadku";
                    $stmt = $pdo->prepare($query);
                    $stmt->bindParam(':id_upadku', $id_upadku);
                    $stmt->execute();
                    $pliki_upadki = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
                    echo "<td>";
                    if (count($pliki_upadki) > 0) {
                        echo "<a href='zalaczniki_upadki.php?id=$id_upadku&ids=$id_stada&idf=$idf' target='_blank'>Zobacz załączniki</a><br>";
                    } else {
                        echo "Brak załączników";
                    }
                    echo "</td>";
    
                    echo "</tr>";
                }
    
                echo "</tbody>";
                echo "</table>";
            } else {
                echo "<div class='no-data'>Brak informacji o upadkach dla danego stada.</div>";
            }
        } else {
            echo "<div class='no-data'>Nie przekazano ID stada.</div>";
        }
        echo "</div>";
    
    
        ?>
    
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
    </body>
    
    </html>