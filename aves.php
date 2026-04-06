<?php
require_once 'login.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Glucose-6-phosphatase (Aves)</title>

    <!-- Global stylesheet -->
    <link rel="stylesheet" href="style_sheet.css">

    <!-- Page-specific styles -->
    <style>
        .container {
            padding: 40px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
        }

        /* Table styling */
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

        /* Changed hover color to purple */
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

<!-- Navbar -->
<div class="navbar">
    <a href="index.php">Home</a>
    <a href="aves.php">Example Dataset</a>
    <a href="analysis_UI.php">Input Page</a>
    <a href="credits.php">Credit Page</a>
    <a href="about.php">About</a>
    <a href="help.php">Help</a>
</div>

<div class="container">

<?php
try {
    $stmt = $pdo->query("SELECT accession, description, length FROM aves_g6p");

    echo "<h2>Example: Glucose-6-phosphatase (Aves)</h2>";

    echo "<table>";
    echo "<tr>
            <th>Accession</th>
            <th>Description</th>
            <th>Length</th>
          </tr>";

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['accession']) . "</td>";
        echo "<td>" . htmlspecialchars($row['description']) . "</td>";
        echo "<td>" . htmlspecialchars($row['length']) . "</td>";
        echo "</tr>";
    }

    echo "</table>";

} catch (PDOException $e) {
    echo "<p class='error'>Database error: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>

</div>

</body>
</html>
