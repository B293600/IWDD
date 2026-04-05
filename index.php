<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Protein Analysis Tool</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f9;
        }

        html {
            scroll-behavior: smooth;
        }

        /* Navbar */
        .navbar {
            background: #1f2937;
            padding: 15px;
            text-align: center;
        }

        .navbar a {
            color: white;
            margin: 0 15px;
            text-decoration: none;
            font-weight: bold;
        }

        .navbar a:hover {
            color: #38bdf8;
        }

        /* Hero Section */
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

        .hero-content {
            animation: fadeIn 1.5s ease-in-out;
        }

        .hero-content h1 {
            font-size: 3rem;
            margin-bottom: 10px;
        }

        .cta-button {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 25px;
            background: #38bdf8;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: 0.3s;
        }

        .cta-button:hover {
            background: #0ea5e9;
            transform: scale(1.05);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Container */
        .container {
            padding: 40px;
            text-align: center;
        }

        /* Features */
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

        .card:hover {
            transform: translateY(-10px);
        }

        /* Stats */
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

        /* How it works */
        .how-it-works {
            padding: 40px;
            text-align: center;
        }

        .how-it-works ol {
            display: inline-block;
            text-align: left;
            margin-top: 20px;
        }

        /* Footer */
        footer {
            background: #111827;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 40px;
        }
    </style>
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

<!-- Hero Section -->
<div class="hero">
    <div class="hero-content">
        <h1>Protein Sequence Analysis Website</h1>
        <p>Analyze, visualize, and explore protein sequences with ease.</p>
        <a href="analysis_UI.php" class="cta-button">Start Analysis</a>
    </div>
</div>

<!-- Intro -->
<div class="container">
    <h2>Welcome</h2>
    <p>Welcome to the protein analysis tool. This platform allows you to input sequences, explore datasets, and perform structured biological analysis.</p>
</div>

<!-- Features -->
<div class="features">
    <div class="card">
        <h3>Sequence Input</h3>
        <p>Paste or upload protein sequences for analysis.</p>
    </div>

    <div class="card">
        <h3>Dataset Exploration</h3>
        <p>View example datasets and structured outputs.</p>
    </div>

    <div class="card">
        <h3>Analysis Tools</h3>
        <p>Run computations and visualize protein properties.</p>
    </div>
</div>

<!-- Stats -->
<div class="stats">
    <div>
        <h2>100+</h2>
        <p>Sequences Analyzed</p>
    </div>
    <div>
        <h2>10+</h2>
        <p>Datasets</p>
    </div>
    <div>
        <h2>5</h2>
        <p>Analysis Tools</p>
    </div>
</div>

<!-- How It Works -->
<div class="how-it-works">
    <h2>How It Works</h2>
    <ol>
        <li>Input a protein sequence</li>
        <li>Select analysis tools</li>
        <li>View computed results and insights</li>
    </ol>
</div>

<!-- Footer -->
<footer>
    <p>© 2026 Protein Analysis Tool</p>
</footer>

</body>
</html>
