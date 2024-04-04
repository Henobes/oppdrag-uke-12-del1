<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticketsystem</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        h1 {
            color: #333;
            text-align: center;
            margin-top: 20px;
        }

        h2 {
            color: #333;
            text-align: center;
            margin-top: 20px;
        }

        a {
            display: block;
            margin-bottom: 10px;
            text-decoration: none;
            color: blue;
            text-align: center;
        }

        form {
            background-color: #fff;
            padding: 20px;
            margin: 20px auto;
            max-width: 400px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        textarea {
            width: calc(100% - 16px);
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
            box-sizing: border-box;
        }

        button[type="submit"] {
            background-color: #4caf50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            width: calc(100% - 16px);
            display: block;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }

        button[type="submit"]:focus {
            outline: none;
        }

        button[type="submit"]:active {
            background-color: #3e8e41;
        }

        .message {
            margin-top: 20px;
            padding: 10px;
            background-color: #dff0d8;
            border: 1px solid #d6e9c6;
            border-radius: 3px;
            color: #3c763d;
            text-align: center;
        }

        .error {
            margin-top: 20px;
            padding: 10px;
            background-color: #f2dede;
            border: 1px solid #ebccd1;
            border-radius: 3px;
            color: #a94442;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Ticketsystem</h1>
    <a href="index.php">Gå til hjemmesiden</a>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <label for="name">Navn:</label>
        <input type="text" id="name" name="name" required><br>

        <label for="password">Passord:</label>
        <input type="password" id="password" name="password" required><br>

        <label for="email">E-post:</label>
        <input type="email" id="email" name="email" required><br>

        <label for="description">Beskrivelse av problemet:</label><br>
        <textarea id="description" name="description" rows="4" cols="50" required></textarea><br>

        <button type="submit">Send inn henvendelse</button>
    </form>

    <h2>Sjekk status på henvendelse</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET">
        <label for="ticket_number">Saksnummer:</label>
        <input type="text" id="ticket_number" name="ticket_number" required><br>
        <button type="submit">Sjekk status</button>
    </form>

    <?php
    include 'database.php';

    // Sjekk om skjemaet er sendt inn via POST-metoden
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Henter verdier fra skjemaet
        $name = $_POST["name"];
        $password = $_POST["password"];
        $email = $_POST["email"];
        $description = $_POST["description"];

        // Legg til henvendelsen i hendvendesler-tabellen
        $sql_insert_henvendelse = "INSERT INTO hendvendesler (beskrivelse, brukerID, statusID, idAnsatte) VALUES ('$description', NULL, NULL, NULL)";

        if ($conn->query($sql_insert_henvendelse) === TRUE) {
            // Hent det genererte saksnummeret
            $saksnummer = $conn->insert_id;
            echo "<div class='message'>Henvendelsen ble sendt inn suksessfullt. Ditt saksnummer er: $saksnummer</div>";

            // Legg til kunden i kunde-tabellen med det tildelte saksnummeret
            $sql_insert_kunde = "INSERT INTO kunde (navn, password, email, saksnummer) VALUES ('$name', '$password', '$email', '$saksnummer')";

            if ($conn->query($sql_insert_kunde) === TRUE) {
                echo "<div class='message'>Kunden ble lagt til suksessfullt.</div>";
            } else {
                echo "<div class='error'>Feil: " . $sql_insert_kunde . "<br>" . $conn->error . "</div>";
            }
        } else {
            echo "<div class='error'>Feil: " . $sql_insert_henvendelse . "<br>" . $conn->error . "</div>";
        }

        // Lukk tilkoblingen til databasen
        $conn->close();
    }

    // Sjekk status på henvendelse
    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['ticket_number'])) {
        // Hent verdien fra skjemaet
        $ticket_number = $_GET["ticket_number"];

        // Spørring for å hente status basert på saksnummer
        $sql_get_status = "SELECT status FROM status WHERE statusID = (SELECT statusID FROM hendvendesler WHERE saksnummer = '$ticket_number')";

        // Utfør spørringen
        $result = $conn->query($sql_get_status);

        // Sjekker om spørringen var vellykket
        if ($result) {
            // Sjekk om det finnes rader som matcher saksnummeret
            if ($result->num_rows > 0) {
                // Henter raden med saksnummeret
                $row = $result->fetch_assoc();
                $status = $row["status"];
                echo "<div class='message'>Status for henvendelse med saksnummer $ticket_number er: $status</div>";
            } else {
                echo "<div class='error'>Ingen henvendelse funnet med saksnummer $ticket_number</div>";
            }
        } else {
            echo "<div class='error'>Feil: " . $sql_get_status . "<br>" . $conn->error . "</div>";
        }
    }
    ?>
</body>
</html>
