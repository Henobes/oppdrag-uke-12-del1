<?php
include 'database.php';

// Sjekk om skjemaet er sendt inn via GET-metoden
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Sjekk om saksnummeret er angitt i URL-parametere
    if(isset($_GET['ticket_number'])) {
        $saksnummer = $_GET["ticket_number"];

        // Hent statusen for henvendelsen basert på saksnummeret
        $sql = "SELECT status FROM hendvendesler WHERE saksnummer = '$saksnummer'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Hvis henvendelsen ble funnet, vis statusen
            $row = $result->fetch_assoc();
            echo "Status for saksnummer $saksnummer er: " . $row["status"];
        } else {
            // Hvis ingen henvendelse ble funnet med det angitte saksnummeret
            echo "Ingen henvendelse ble funnet med saksnummer $saksnummer.";
        }
    } else {
        // Hvis saksnummeret ikke ble angitt i URL-parametere
        echo "Vennligst angi et saksnummer.";
    }
}
?>
