<?php
// MySQL Database login with PDO
require_once 'login.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<!-- Page metadata and title -->
    <meta charset="UTF-8">
    <title>Glucose-6-phosphatase (Aves)</title>

<!-- Global stylesheet -->
    <link rel="stylesheet" href="style_sheet.css">

<!-- Page specific styles, for container, headings, table formatting and error messages -->
    <style>
        .container {
            padding: 40px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
        }

        table {
            margin: 0 auto;
            border-collapse: collapse;
            width: 90%;
            max-width: 1000px;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
        }

        th {
            background: #1f2937;
            color: white;
        }

        tr:nth-child(even) {
            background: #f2f2f2;
        }

        tr:hover {
            background: #e9d5ff;
        }

        .error {
            color: red;
            margin-top: 20px;
        }
    </style>
</head>

<body>

<!-- Navigation Bar -->
<div class="navbar">
    <a href="index.php">Home</a>
    <a href="aves.php">Example Dataset</a>
    <a href="analysis_UI.php">Input Page</a>
    <a href="credits.php">Credit Page</a>
    <a href="about.php">About</a>
    <a href="help.php">Help</a>
</div>

<!-- Main content container to display the dataset -->
<div class="container">

<?php
// Attempts to query the database to retrieve certain attributes from the aves_g6p table
try {
    $stmt = $pdo->query("SELECT accession, description, length FROM aves_g6p");

    // Displays the page heading
    echo "<h2>Example: Glucose-6-phosphatase (Aves)</h2>";

    // Start HTML table and define column headers
    echo "<table>";
    echo "<tr>
            <th>Accession</th>
            <th>Description</th>
            <th>Length</th>
          </tr>";

    // Loop through each row returned from the database and display it in the table
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['accession']) . "</td>";
        echo "<td>" . htmlspecialchars($row['description']) . "</td>";
        echo "<td>" . htmlspecialchars($row['length']) . "</td>";
        echo "</tr>";
    }

    // Close the table
    echo "</table>";

    // Display error message if database query fails
} catch (PDOException $e) {
    echo "<p class='error'>Database error: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
</div>

</body>
</html>
