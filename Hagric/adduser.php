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
    </style>
</head>
<body>
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

                    
                    $query = "INSERT INTO users (username, nip, password, numer_telefonu, imie, nazwisko, adres) VALUES (:username, :nip, :password, :numer_telefonu, :imie, :nazwisko, :adres)";
                    $stmt = $pdo->prepare($query);
                    $stmt->bindParam(':username', $username);
                    $stmt->bindParam(':nip', $nip);
                    $stmt->bindParam(':password', $password);
                    $stmt->bindParam(':numer_telefonu', $numer_telefonu);
                    $stmt->bindParam(':imie', $imie);
                    $stmt->bindParam(':nazwisko', $nazwisko);
                    $stmt->bindParam(':adres', $adres);
                    $stmt->execute();

                    
                    $pdo->commit();

                    
                    header("Location: index.php");
                    exit();
                } catch (PDOException $e) {
                    
                    $pdo->rollBack();
                    echo "Błąd zapytania: " . $e->getMessage();
                }
            }

echo "<a href='index.php' class='powrot'; style='color: black;'>Powrót</a>";
?>



<h1>Dodaj użytkownika:</h1>

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

<script>
        const currentYear = new Date().getFullYear();
        document.querySelector(".current-year").textContent = currentYear;
    </script>
</body>
</html>
