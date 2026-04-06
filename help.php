<?php
require_once 'login.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Help - ProteinQuery</title>

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

        h3 {
            margin-top: 30px;
            margin-bottom: 10px;
        }

        p {
            max-width: 800px;
            margin: 0 auto 15px auto;
            line-height: 1.6;
        }

        ul {
            display: inline-block;
            text-align: left;
            margin-top: 10px;
        }

        li {
            margin-bottom: 8px;
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

    <h2>Help - ProteinQuery</h2>

    <h3>Getting Started</h3>
    <p>
        To begin using ProteinQuery, navigate to the Input Page where you can either upload a dataset
        or use an existing one for analysis.
    </p>

    <h3>Using the Input Page</h3>
    <ul>
        <li>Select or upload a protein dataset</li>
        <li>Enter search keywords if required</li>
        <li>Choose one or more analysis tools</li>
        <li>Submit your request to view results</li>
    </ul>

    <h3>Available Analysis Tools</h3>
    <ul>
        <li>Sequence length statistics</li>
        <li>Multiple sequence alignment</li>
        <li>Conservation analysis</li>
        <li>Motif detection</li>
    </ul>

    <h3>Example Dataset</h3>
    <p>
        The Example Dataset page provides a preloaded dataset (Glucose-6-phosphatase in Aves)
        that can be used to explore the functionality of the system without uploading your own data.
    </p>

    <h3>Troubleshooting</h3>
    <ul>
        <li>Ensure your dataset is in the correct format before uploading</li>
        <li>Check that required fields are filled in before submission</li>
        <li>Refresh the page if results do not display correctly</li>
    </ul>

</div>

</body>
</html>
