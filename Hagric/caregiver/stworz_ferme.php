<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,600,0,0" />
    <link href="css/footer.css" rel="stylesheet">
    <title>Dodaj fermę</title>
</head>

<body>
    <div class="login-card-container">
        <div class="login-card">
            <div class="login-card-header">
                <h1>Dodaj fermę</h1>
            </div>

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
            $user_id = $_GET['id'];
            echo "<a href='indexadmin.php?id=$user_id' class='powrot'; style='color: black;'>Powrót</a>";

            
            if (isset($_POST['submit'])) {
                
                $nazwa = $_POST['nazwa'];
                $adres = $_POST['adres'];
                $kontakt = $_POST['kontakt'];
                $wlasciciel = $_POST['wlasciciel'];
                $numer_s = $_POST['numer_s'];
                $przedstawiciel_hagric = $_POST['przedstawiciel_hagric'];
                $dostawca_pasz = $_POST['dostawca_pasz'];
                $przedstawiciel_dostawcy_pasz = $_POST['przedstawiciel_dostawcy_pasz'];
                $lekarz_prowadzacy = $_POST['lekarz_prowadzacy'];
                $user_id = $_GET['id'];

                try {
                    
                    $pdo->beginTransaction();

                    
                    $query = "INSERT INTO lista_ferm (nazwa, adres, kontakt, wlasciciel, numer_s, przedstawiciel_hagric, dostawca_pasz, przedstawiciel_dostawcy_pasz, lekarz_prowadzacy, user_id) VALUES (:nazwa, :adres, :kontakt, :wlasciciel, :numer_s, :przedstawiciel_hagric, :dostawca_pasz, :przedstawiciel_dostawcy_pasz, :lekarz_prowadzacy, :user_id)";
                    $stmt = $pdo->prepare($query);
                    $stmt->bindParam(':nazwa', $nazwa);
                    $stmt->bindParam(':adres', $adres);
                    $stmt->bindParam(':kontakt', $kontakt);
                    $stmt->bindParam(':wlasciciel', $wlasciciel);
                    $stmt->bindParam(':numer_s', $numer_s);
                    $stmt->bindParam(':przedstawiciel_hagric', $przedstawiciel_hagric);
                    $stmt->bindParam(':dostawca_pasz', $dostawca_pasz);
                    $stmt->bindParam(':przedstawiciel_dostawcy_pasz', $przedstawiciel_dostawcy_pasz);
                    $stmt->bindParam(':lekarz_prowadzacy', $lekarz_prowadzacy);
                    $stmt->bindParam(':user_id', $user_id);
                    $stmt->execute();

                   
                    $pdo->commit();

                    
                    header("Location: indexadmin.php?id=$user_id");
                    exit();
                } catch (PDOException $e) {
                    
                    $pdo->rollBack();
                    echo "Błąd zapytania: " . $e->getMessage();
                }
            }
            
            ?>

            <form class="login-card-form" method="POST">
                <div class="form-item">
                    <label>Nazwa:</label>
                    <input type="text" name="nazwa" required><br>
                    <label>Adres:</label>
                    <input type="text" name="adres" required><br>
                    <label>Kontakt:</label>
                    <input type="number" name="kontakt" required><br>
                    <label>Właściciel:</label>
                    <input type="text" name="wlasciciel" required><br>
                    <label>Numer stada:</label>
                    <input type="text" name="numer_s" required><br>
                    <label>Przedstawiciel Hagric:</label>
                    <input type="text" name="przedstawiciel_hagric" required><br>
                    <label>Dostawca pasz:</label>
                    <input type="text" name="dostawca_pasz" required><br>
                    <label>Przedstawiciel dostawcy pasz:</label>
                    <input type="text" name="przedstawiciel_dostawcy_pasz" required><br>
                    <label>Lekarz prowadzący:</label>
                    <input type="text" name="lekarz_prowadzacy" required><br>
                    <button type="submit" name="submit">Dodaj fermę</button>
                </div>
            </form>
        </div>
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
