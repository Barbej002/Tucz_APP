<?php
session_start();

// Sprawdzenie czy użytkownik jest zalogowany
if (!isset($_SESSION['user_id'])) {
    header("Location: /login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">

<title>Lista rolników</title>


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

        tr.odd {
    background-color: rgba(255, 255, 255, 0);
}


tr.even {
    background-color: rgba(200, 200, 200, .4);
}


tr:hover {
    background-color: rgba(255, 153, 0, 0.8); 
}

    </style>
</head>
<body id="page-top">
<nav class="navbar">
<div class="navbar-buttons">
<a href="caregiver.php" class="navbar-button">Powrót</a>
</div>
<a href="#" class="navbar-logo">Informacje o rolnikach</a>
<div class="navbar-buttons">
            <a href="logout.php" class="navbar-button">Wyloguj</a>
        </div>
    </nav>
    
<div id="wrapper">
    


<div id="content-wrapper" class="d-flex flex-column">

    
    <div id="content">

        
        <div class="container-fluid">
            

            

            

                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Lista rolników</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                        <tr>
                                            <th>Imię</th>
                                            <th>Nazwisko</th>
                                            <th>Adres</th>
                                            <th>NIP</th>
                                            <th>Numer telefonu</th>
                                        </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                            <th>Imię</th>
                                            <th>Nazwisko</th>
                                            <th>Adres</th>
                                            <th>NIP</th>
                                            <th>Numer telefonu</th>
                                        </tr>
                            </tfoot>
                            <tbody>
                            <?php
// Połączenie z bazą danych
require_once('db_config.php');

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    $logged_in_caregiver_id = $_SESSION['user_id']; 


    $sql = "SELECT u.id, u.imie, u.nazwisko, u.adres, u.nip, u.numer_telefonu 
            FROM users u
            INNER JOIN caregivers c ON u.id = c.farmer_id
            WHERE c.caregiver_id = :caregiver_id";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':caregiver_id', $logged_in_caregiver_id);
    $stmt->execute();
    
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($stmt->rowCount() > 0) {

        
        foreach ($result as $row) {
            echo "<tr class='$row_class' onclick=\"window.location='indexadmin.php?id=" . $row["id"] . "'\">";
            echo "<td>" . $row['imie'] . "</td>";
            echo "<td>" . $row['nazwisko'] . "</td>";
            echo "<td>" . $row['adres'] . "</td>";
            echo "<td>" . $row['nip'] . "</td>";
            echo "<td>" . $row['numer_telefonu'] . "</td>";
            echo "</tr>";
        }
        
        echo '</table>';
    } else {
        echo "Brak rolników przypisanych do opiekuna.";
    }
} catch (PDOException $e) {
    echo "Błąd zapytania: " . $e->getMessage();
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



<a class="scroll-to-top rounded" href="#page-top">
<i class="fas fa-angle-up"></i>
</a>


<div class="footer" style="padding-top:12px">
    <p>
        <span class="footer-text">&copy;</span>
        <span class="current-year">Year</span>
        <span class="footer-text";>Hagric - Developed by <a href="https://www.ac-it.pl/" target="_blank" class="footer-link">AC IT Sp. z o.o.</a></span>
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