<?php
// Dane do połączenia z bazą danych
$host = "mysql8";
$dbname = "37328198_fermy";
$username = "37328198_fermy";
$password = "R&b^7C!pD*2@";

// Łączenie z bazą danych
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Nie można połączyć się z bazą danych: " . $e->getMessage());
}

// Pobranie danych z formularza
$login = $_POST['username'];
$password = $_POST['password'];

// Wyszukanie użytkownika w bazie danych
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username AND password = :password");
$stmt->execute(array(':username' => $login, ':password' => $password));
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Sprawdzenie czy użytkownik istnieje i czy podane hasło jest poprawne
if ($user === false) {
    // Przekierowanie na stronę logowania z komunikatem o błędnym loginie lub haśle
    header("Location: login.html?error=1");
    exit();
} else {
    // Rozpoczęcie sesji i zapisanie id użytkownika
    session_start();
    $_SESSION['logged_in'] = true;
    $_SESSION['user_id'] = $user['id'];
    
    // Przekierowanie na stronę główną
    header("Location: index.php");
    exit();
}
?>
