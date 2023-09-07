
<!DOCTYPE html>
<html lang="pl">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">

<title>Lista rolników</title>

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
        tr.odd {
    background-color: rgba(255, 255, 255, 0); /* Tutaj możesz ustawić kolor dla nieparzystych wierszy */
}

/* Styl dla parzystych wierszy */
tr.even {
    background-color: rgba(200, 200, 200, .4); /* Tutaj możesz ustawić kolor dla parzystych wierszy */
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
        /* Styl dla nieparzystych wierszy */
tr.odd {
    background-color: rgba(255, 255, 255, 0);
}

/* Styl dla parzystych wierszy */
tr.even {
    background-color: rgba(200, 200, 200, .4);
}

/* Efekt przyciemnienia po najechaniu */
tr:hover {
    background-color: rgba(255, 153, 0, 0.8); /* Tutaj możesz ustawić kolor tła po najechaniu */
}



    </style>
</head>
<body id="page-top">
<nav class="navbar">
<div class="navbar-buttons">
<a href="panel_administratora2.php" class="navbar-button">Powrót</a>
</div>
<a href="#" class="navbar-logo">Informacje o rolnikach</a>
<div class="navbar-buttons">
            <a href="/logout.php" class="navbar-button">Wyloguj</a>
        </div>
    </nav>
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
                                        $counter = 0;
                                        // Connect to the database
                                        $conn = mysqli_connect("mysql8", "37328198_fermy", "R&b^7C!pD*2@", "37328198_fermy");

                                        // Check connection
                                        if ($conn->connect_error) {
                                            die("Connection failed: " . $conn->connect_error);
                                        }

                                        // Fetch farmers from the database
                                        $sql = "SELECT id, imie, nazwisko, adres, nip, numer_telefonu FROM users";
                                        $result = $conn->query($sql);

                                        if ($result->num_rows > 0) {
                                            // Output data of each farmer
                                            while ($row = $result->fetch_assoc()) {
                                                // Sprawdzenie, czy wiersz jest parzysty czy nieparzysty
                                                $row_class = ($counter % 2 === 0) ? 'even' : 'odd';
                                                $counter++;
                                                echo "<tr class='$row_class' onclick=\"window.location='indexadmin.php?id=" . $row["id"] . "'\">";
                                                echo "<td>" . $row["imie"] . "</td>";
                                                echo "<td>" . $row["nazwisko"] . "</td>";
                                                echo "<td>" . $row["adres"] . "</td>";
                                                echo "<td>" . $row["nip"] . "</td>";
                                                echo "<td>" . $row["numer_telefonu"] . "</td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='5'>No farmers found</td></tr>";
                                        }

                                        // Close the database connection
                                        $conn->close();
                                        ?>
                                    </tbody>
                        </table>
                    </div>
                </div>

        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- Koniec głównej zawartości -->

</div>
<!-- Koniec kontenera zawartości -->

</div>
<!-- Koniec zawartości strony -->

<!-- Przycisk przewijania do góry -->
<a class="scroll-to-top rounded" href="#page-top">
<i class="fas fa-angle-up"></i>
</a>

<div class="kontener">
            <div class="footer" style="padding-top: 12px;">
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