<?php

$host = "mysql8"; 
$username = "37328198_fermy"; 
$password = "R&b^7C!pD*2@"; 
$database = "37328198_fermy"; 


$conn = mysqli_connect($host, $username, $password, $database);


if (!$conn) {
    die("Błąd połączenia z bazą danych: " . mysqli_connect_error());
}
?>
