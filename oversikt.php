<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oversikt over Henvendelser</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Oversikt over Henvendelser</h1>
    <a href="index.php">Gå til hovedsiden</a>
    <a href="ansatte.php">Tilbake</a>
    <table>
        <tr>
            <th>Saksnummer</th>
            <th>Beskrivelse</th>
            <th>Status</th>
        </tr>

        <?php
        // Inkluder databaseforbindelse
        include 'database.php';

        // Henter alle henvendelser med tilhørende status
        $sql_get_henvendelser = "SELECT hendvendesler.saksnummer, hendvendesler.beskrivelse, status.status FROM hendvendesler INNER JOIN status ON hendvendesler.statusID = status.statusID";
        $result = $conn->query($sql_get_henvendelser);

        if ($result->num_rows > 0) {
            // Går gjennom hver rad og skriv ut data i tabellen
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["saksnummer"] . "</td>";
                echo "<td>" . $row["beskrivelse"] . "</td>";
                echo "<td>" . $row["status"] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3'>Ingen henvendelser funnet.</td></tr>";
        }
        ?>
    </table>
</body>
</html>
