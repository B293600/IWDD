
<?php
// Start session so that user data entered in the web_login.php page can be displayed
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<!-- Page metadata and title -->
    <meta charset="UTF-8">
    <title>Protein Analysis Tool</title>

<!-- Links to global style sheet -->
    <link rel="stylesheet" href="style_sheet.css">

<!-- Page specific CSS styling -->
    <style>
        html {
            scroll-behavior: smooth;
        }
<!-- Adds hero image -->
        .hero {
            height: 65vh;
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)),
                        url('https://images.unsplash.com/photo-1581091870622-1e7c2c2b2d8f') center/cover no-repeat;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
        }
<!-- Adds fading animation -->
        .hero-content {
            animation: fadeIn 1.5s ease-in-out;
        }

        .site-title {
            font-size: 3.5rem;
            font-weight: bold;
            margin-bottom: 10px;
            letter-spacing: 2px;
        }

        .hero-content h1 {
            font-size: 2.2rem;
            margin-bottom: 10px;
        }

        .container {
            padding: 40px;
            text-align: center;
        }

        .features {
            display: flex;
            justify-content: center;
            gap: 20px;
            padding: 40px;
            flex-wrap: wrap;
        }

        .card {
            background: white;
            padding: 20px;
            width: 260px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
<!-- Adds card animation -->
        .card:hover {
            transform: translateY(-10px);
        }

        .stats {
            display: flex;
            justify-content: center;
            gap: 60px;
            padding: 40px;
            background: #1f2937;
            color: white;
            text-align: center;
        }

        .stats h2 {
            font-size: 2rem;
            margin-bottom: 5px;
        }

        .how-it-works {
            padding: 40px;
            text-align: center;
        }

        .how-it-works ol {
            display: inline-block;
            text-align: left;
            margin-top: 20px;
        }

        footer {
            background: #111827;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 40px;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .hero-button {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>

<body>

<!-- Navigation header -->
<div class="navbar">
    <a href="index.php">Home</a>
    <a href="aves.php">Example Dataset</a>
    <a href="analysis_UI.php">Input Page</a>
    <a href="credits.php">Credit Page</a>
    <a href="about.php">About</a>
    <a href="help.php">Help</a>
</div>

<!-- Adds Hero section -->
<div class="hero">
    <div class="hero-content">

        <div class="site-title">ProteinQuery</div>

        <h1>
            <?php
            if (isset($_SESSION['fn']) && isset($_SESSION['sn'])) {
                echo "Welcome " . htmlspecialchars($_SESSION['fn']) . " " . htmlspecialchars($_SESSION['sn']) . "!";
            } elseif (isset($_SESSION['fn'])) {
                echo "Welcome " . htmlspecialchars($_SESSION['fn']) . "!";
            } else {
                echo "Protein Sequence Analysis Website";
            }
            ?>
        </h1>

        <p>Analyse, visualise and explore protein sequences.</p>
<!-- Button that directs user to the analysis input page -->
        <form action="analysis_UI.php" method="get">
            <button type="submit" class="btn hero-button">Start Analysis</button>
        </form>

    </div>
</div>

<!-- Explain what the platform does -->
<div class="container">
    <p>
        This platform enables biological analysis of amino acid sequences retrieved through keyword searches across different taxonomic groups.
    </p>
</div>

<!-- Highlights main capabilities using cards -->
<div class="features">
    <div class="card">
        <h3>Sequence Retrieval</h3>
        <p>Retrieve protein sequences using keyword and taxonomy-based searches.</p>
    </div>

    <div class="card">
        <h3>Example Dataset</h3>
        <p>Explore the example dataset of Glucose-6-phosphatase proteins from Aves</p>
    </div>

    <div class="card">
        <h3>Analysis Tools</h3>
        <p>Retrieve protein sequences using keyword and taxonomy-based searches.</p>
    </div>
</div>

<!-- Shows the tools available to the user -->
<div class="stats">
    <div>
        <p>Length Statistics</p>
    </div>
    <div>
        <p>Multiple Sequence Alignment</p>
    </div>
    <div>
        <p>Conservation Plot</p>
    </div>
    <div>
        <p>Motif Finder</p>
    </div>
</div>

<!-- Brief instructions-->
<div class="how-it-works">
    <h2>How It Works</h2>
    <ol>
        <li>Select a new or existing dataset</li>
        <li>Select analysis tools</li>
        <li>View results and insights</li>
    </ol>
</div>

<!-- Footer tag -->
<footer>
    <p>Protein Analysis Tool - ProteinQuery</p>
</footer>

</body>
</html>
