<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ansatteside - Oppdater Status</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            max-width: 400px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }

        a {
            display: block;
            margin-bottom: 10px;
            text-decoration: none;
            color: blue;
            text-align: center;
        }

        form {
            margin-top: 20px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        select {
            width: 100%;
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
            width: 100%;
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
    <div class="container">
        <h1>Oppdater Status for Henvendelser</h1>
        <a href="index.php">Gå til hovedsiden</a>
        <a href="oversikt.php">Gå til oversikt av henvendelser</a>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <label for="ticket_number">Saksnummer:</label>
            <input type="text" id="ticket_number" name="ticket_number" required><br>
            
            <label for="status">Status:</label>
            <select id="status" name="status" required>
                <option value="1">Åpen</option>
                <option value="2">Under behandling</option>
                <option value="3">Løst</option>
            </select><br>

            <button type="submit" name="update_status">Oppdater Status</button>
            <button type="submit" name="delete_ticket">Slett Sak</button>
        </form>

        <?php
        // Inkluder databaseforbindelse
        include 'database.php';

        // Sjekk om skjemaet er sendt inn via POST-metoden
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
           
            if (isset($_POST['update_status'])) {
                
                $ticket_number = $_POST["ticket_number"];
                $status = $_POST["status"];

      
                if (!in_array($status, array(1, 2, 3))) {
                    echo "<div class='error'>Ugyldig status valgt.</div>";
                    exit;
                }

               
                $sql_update_status = "UPDATE hendvendesler SET statusID = '$status' WHERE saksnummer = '$ticket_number'";

                if ($conn->query($sql_update_status) === TRUE) {
                    
                    $sql_get_status_name = "SELECT status FROM status WHERE statusID = '$status'";
                    $result = $conn->query($sql_get_status_name);
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $status_name = $row["status"];
                        echo "<div class='message'>Status på saksnummer $ticket_number endret til $status_name.</div>";
                    } else {
                        echo "<div class='error'>Feil: Kunne ikke hente statusnavn fra databasen.</div>";
                    }
                } else {
                    echo "<div class='error'>Feil: " . $sql_update_status . "<br>" . $conn->error . "</div>";
                }
            }


            if (isset($_POST['delete_ticket'])) {
            
                $ticket_number = $_POST["ticket_number"];

                $sql_delete_ticket = "DELETE FROM hendvendesler WHERE saksnummer = '$ticket_number'";

                if ($conn->query($sql_delete_ticket) === TRUE) {
                    echo "<div class='message'>Saken med saksnummer $ticket_number ble slettet.</div>";
                } else {
                    echo "<div class='error'>Feil: " . $sql_delete_ticket . "<br>" . $conn->error . "</div>";
                }
            }

            $conn->close();
        }
        ?>
    </div>
</body>
</html>
