<!DOCTYPE html>
<html lang="en">

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
    <title>Lista rolników</title>
</head>

<body id="page-top">


    <div id="wrapper">



        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">



                <div class="container-fluid">


                    <h1 class="h3 mb-2 text-gray-800">Rolnicy</h1>


                    <div class="card shadow mb-4">
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
                                       
                                       require_once('db_config.php');

                                       try {
                                           $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
                                           $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                       } catch (PDOException $e) {
                                           die("Nie można połączyć się z bazą danych: " . $e->getMessage());
                                       }                                       

                                        
                                        $sql = "SELECT imie, nazwisko, adres, nip, numer_telefonu FROM users";
                                        $result = $conn->query($sql);

                                        if ($result->num_rows > 0) {
                                            
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr>";
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

                                        
                                        $conn->close();
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                

            </div>
            

            
        </div>
        

    </div>
   

    

</body>

</html>
