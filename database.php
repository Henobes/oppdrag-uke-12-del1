<?php

$server = "localhost";
$user = "root";
$pw = "Admin";
$db = "uke12oppdrag";

// Opprett tilkobling
$conn = mysqli_connect($server, $user, $pw, $db);

// Sjekk tilkoblingen
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Sett tegnsettet til UTF-8 for å sikre riktig håndtering av spesialtegn
mysqli_set_charset($conn, "utf8");

?>
