<?php
require_once 'login.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Credits - ProteinQuery</title>

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

        a {
            color: #1f2937;
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

    <h2>Credits</h2>

    <h3>Project</h3>
    <p>
        ProteinQuery was developed as a web-based bioinformatics tool for the retrieval and analysis of protein sequences.
    </p>

    <h3>Data Sources</h3>
    <ul>
        <li>Protein sequence data retrieved from public biological databases</li>
        <li>Reference datasets used for demonstration and testing purposes</li>
    </ul>

    <h3>Tools & Technologies</h3>
    <ul>
        <li>PHP for server-side scripting</li>
        <li>MySQL for database management</li>
        <li>HTML, CSS, and JavaScript for frontend design</li>
    </ul>

    <h3>External Resources</h3>
    <ul>
        <li>Unsplash (images used in UI design)</li>
        <li>Open-source web development resources and documentation</li>
    </ul>

    <h3>Acknowledgements</h3>
    <p>
        Special thanks to instructors, peers, and contributors who supported the development of this project.
    </p>

</div>

</body>
</html>
