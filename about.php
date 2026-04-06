<?php
require_once 'login.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About ProteinQuery</title>

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

    <h2>About ProteinQuery</h2>

    <h3>Overview</h3>
    <p>
        ProteinQuery is a web-based platform designed for the retrieval and analysis of protein sequences
        across different taxonomic groups using keyword-based searches.
    </p>

    <h3>Purpose</h3>
    <p>
        The purpose of this tool is to provide users with an accessible interface to explore amino acid
        sequences and perform common bioinformatics analyses.
    </p>

    <h3>Features</h3>
    <ul>
        <li>Protein sequence retrieval using keywords</li>
        <li>Taxonomy-based filtering</li>
        <li>Multiple sequence alignment</li>
        <li>Sequence length analysis</li>
        <li>Motif detection</li>
    </ul>

    <h3>How It Works</h3>
    <p>
        Users can navigate to the input page, select or upload datasets, and choose the desired analysis tools.
        The results are then processed and displayed for interpretation.
    </p>

</div>

</body>
</html>
