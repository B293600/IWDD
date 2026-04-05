<?php
require_once 'login.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <title>Glucose-6-phosphatase (Aves)</title>
</head>

<body>

<div class="navbar">
    <a href="index.php">Home</a>
    <a href="aves.php">Example Dataset</a>
    <a href="analysis_UI.php">Input Page</a>
    <a href="credits.php">Credit Page</a>
    <a href="about.php">About</a>
    <a href="help.php">Help</a>
</div>

<?php
try {
    $stmt = $pdo->query("SELECT accession, description, length FROM aves_g6p");

    echo "<h2>Example: Glucose-6-phosphatase (Aves)</h2>";

    echo "<table border='1' cellpadding='5' cellspacing='0'>";
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
    echo "<p>Database error: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>

</body>
</html>
