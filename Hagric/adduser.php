<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="zglosupadek.css"/>
    <link href="css/footer.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,600,0,0" />
    <title>Dodaj rolnika</title>
    <style>
            body {
        background: linear-gradient(to right, #ff8800, #7c2e00);
    }
    .search-container {
    display: flex;
    flex-direction: column;
}

.search {
    margin-bottom: 10px;
    padding: 5px;
    border-radius: 5px;
    border: 1px solid #ccc;
}

.farmers-container {
    max-height: 200px;
    overflow-y: auto;
    background: rgba(255, 255, 255, .3);
    border-radius: 3px;
}

.farmers-container label {
    display: block;
    margin-bottom: 5px;
}
    </style>
</head>
<body>
<a href='index.php' class='powrot' style='color: black;'>Powrót</a>
<div class="login-card-container">
<div class="login-card">
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

            if (isset($_POST['submit'])) {
                $username = $_POST['username'];
                $nip = $_POST['nip'];
                $password = $_POST['password'];
                $numer_telefonu = $_POST['numer_telefonu'];
                $imie = $_POST['imie'];
                $nazwisko = $_POST['nazwisko'];
                $adres = $_POST['adres'];

                try {
                    $pdo->beginTransaction();

                    // Wstawienie danych użytkownika do bazy
                    $query_insert_user = "INSERT INTO users (username, nip, password, numer_telefonu, imie, nazwisko, adres) VALUES (:username, :nip, :password, :numer_telefonu, :imie, :nazwisko, :adres)";
                    $stmt_insert_user = $pdo->prepare($query_insert_user);
                    $stmt_insert_user->bindParam(':username', $username);
                    $stmt_insert_user->bindParam(':nip', $nip);
                    $stmt_insert_user->bindParam(':password', $password);
                    $stmt_insert_user->bindParam(':numer_telefonu', $numer_telefonu);
                    $stmt_insert_user->bindParam(':imie', $imie);
                    $stmt_insert_user->bindParam(':nazwisko', $nazwisko);
                    $stmt_insert_user->bindParam(':adres', $adres);
                    $stmt_insert_user->execute();

                    // Pobranie ID ostatnio dodanego użytkownika
                    $user_id = $pdo->lastInsertId();

                    // Przypisanie wybranego rolnika do opiekuna
                    if (isset($_POST['farmers']) && is_array($_POST['farmers'])) {
                        $selectedFarmers = $_POST['farmers'];
                        foreach ($selectedFarmers as $farmer_id) {
                            $query_assign_caregiver = "INSERT INTO caregivers (caregiver_id, farmer_id) VALUES (:caregiver_id, :farmer_id)";
                            $stmt_assign_caregiver = $pdo->prepare($query_assign_caregiver);
                            $stmt_assign_caregiver->bindParam(':caregiver_id', $farmer_id);
                            $stmt_assign_caregiver->bindParam(':farmer_id', $user_id);
                            $stmt_assign_caregiver->execute();
                        }
                    }

                    $pdo->commit();

                    header("Location: index.php");
                    exit();
                } catch (PDOException $e) {
                    $pdo->rollBack();
                    echo "Błąd zapytania: " . $e->getMessage();
                }
            }
            ?>



<h1>Dodaj rolnika:</h1>

<form method="post">
    <label for="username">E-mail:</label>
    <input type="text" name="username" value="<?php echo $row['username']; ?>"><br>

    <label for="nip">NIP:</label>
    <input type="number" name="nip" value="<?php echo $row['nip']; ?>"><br>
    
    <label for="password">Hasło:</label>
    <input type="password" name="password" value="<?php echo $row['password']; ?>"><br>

    <label for="numer_telefonu">Numer telefonu:</label>
    <input type="number" name="numer_telefonu" value="<?php echo $row['numer_telefonu']; ?>"><br>
    
    <label for="imie">Imię:</label>
    <input type="text" name="imie" value="<?php echo $row['imie']; ?>"><br>

    <label for="nazwisko">Nazwisko:</label>
    <input type="text" name="nazwisko" value="<?php echo $row['nazwisko']; ?>"><br>

    <label for="adres">Adres:</label>
    <input type="text" name="adres" value="<?php echo $row['adres']; ?>"><br>

    <label>Wybierz opiekuna:</label><br>
    <div class="search-container">
    <input class="search" type="text" id="searchFarmers" placeholder="Wyszukaj opiekuna...">
    <div class="farmers-container" id="farmersContainer">
                    <?php
                    try {
                        $pdo = new PDO("mysql:host=mysql8;dbname=37328198_fermy;charset=utf8", '37328198_fermy', 'R&b^7C!pD*2@');
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        $query_farmers = "SELECT id, username FROM users WHERE administrator = 'caregiver'";
                        $stmt_farmers = $pdo->prepare($query_farmers);
                        $stmt_farmers->execute();
                        $farmers = $stmt_farmers->fetchAll();

                        foreach ($farmers as $farmer) {
                            echo "<label><input type='checkbox' name='farmers[]' value='" . $farmer['id'] . "' >" . $farmer['username'] . "</label><br>";
                        }
                    } catch (PDOException $e) {
                        echo "Błąd zapytania: " . $e->getMessage();
                    }
                    ?>
                </div>

    <button type="submit" name="submit">Dodaj</button>
</form>
</div>
</div>
<a href="logout.php" class="wyloguj">Wyloguj</a>

<div class="kontener">
    <div class="footer">
        <p><span class="footer-text">&copy;</span> <span class="current-year">Year</span> <span class="footer-text">Hagric - Developed by <a href="https://www.ac-it.pl/" target="_blank" class="footer-link">AC IT Sp. z o.o.</a></span></p>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
        const currentYear = new Date().getFullYear();
        document.querySelector(".current-year").textContent = currentYear;
    </script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var searchFarmers = document.getElementById('searchFarmers');
        var farmersContainer = document.getElementById('farmersContainer');
        var labels = farmersContainer.querySelectorAll('.farmers-container label');

        searchFarmers.addEventListener('input', function() {
            var filter = this.value.toUpperCase();
            var visibleLabels = [];

            labels.forEach(function(label) {
                var txtValue = label.textContent || label.innerText;
                var isVisible = txtValue.toUpperCase().indexOf(filter) > -1;
                label.style.display = isVisible ? 'block' : 'none';

                if (isVisible) {
                    visibleLabels.push(label);
                }
            });

            visibleLabels.forEach(function(label) {
                farmersContainer.prepend(label); 
            });

            var noResults = farmersContainer.querySelector('.no-results');

            if (visibleLabels.length === 0) {
                if (!noResults) {
                    noResults = document.createElement('p');
                    noResults.classList.add('no-results');
                    farmersContainer.appendChild(noResults);
                }
            } else {
                if (noResults) {
                    noResults.remove();
                }
            }
        });
    });
</script>
</body>
</html>
