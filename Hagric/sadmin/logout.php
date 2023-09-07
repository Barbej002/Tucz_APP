<?php
// Rozpoczęcie sesji
session_start();

// Usunięcie zapisanego id użytkownika
unset($_SESSION['user_id']);

// Zakończenie sesji
session_destroy();

// Przekierowanie na stronę logowania
header("Location: login.html");
exit();
?>
