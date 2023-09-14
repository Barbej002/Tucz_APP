<?php

require_once('db_config.php');

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Nie można połączyć się z bazą danych: " . $e->getMessage());
}

$login = $_POST['username'];
$password = $_POST['password'];

$stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username AND password = :password");
$stmt->execute(array(':username' => $login, ':password' => $password));
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user === false) {
    
    header("Location: login.html?error=1");
    exit();
} else {
    session_start();
    $_SESSION['logged_in'] = true;
    $_SESSION['user_id'] = $user['id'];
    header("Location: index.php");
    exit();
}
?>
