<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About</title>

    <!-- Global stylesheet (contains navbar styling and shared styles) -->
    <link rel="stylesheet" href="style_sheet.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 900px;
            margin: auto;
            padding: 40px 20px;
        }

        h2, h3 {
            margin-bottom: 15px;
        }

        p {
            line-height: 1.6;
            margin-bottom: 20px;
        }

        ul {
            list-style-position: inside;
            padding: 0;
            margin-bottom: 20px;
        }

        li {
            margin-bottom: 10px;
        }

        .section {
            margin-bottom: 40px;
        }
    </style>
</head>

<body>

<!-- Navigation bar (styled via global stylesheet only) -->
<div class="navbar">
    <a href="index.php">Home</a>
    <a href="aves.php">Example Dataset</a>
    <a href="analysis_UI.php">Input Page</a>
    <a href="credits.php">Credit Page</a>
    <a href="about.php">About</a>
    <a href="help.php">Help</a>
</div>

<div class="container">

    <h2>About This Website</h2>

    <!-- Overview -->
    <div class="section">
        <h3>Overview</h3>
        <p>
            This website is designed to support biological sequence analysis.
            It allows users to select or search for datasets, perform comparisons and interpret similarity results through a structured user interface.
        </p>
    </div>

    <!-- Architecture -->
    <div class="section">
        <h3>System Architecture</h3>
        <p>
            The website has a multi-page structure where each page is responsible for a specific part of the workflow, including data input, analysis, results and documentation.
        </p>
        <p>
            A consistent navigation system is used across all pages to allow users to move easily between different sections of the application.
        </p>
    </div>

    <!-- Data handling -->
    <div class="section">
        <h3>Data Handling</h3>
        <p>
            Sequence data can be provided either by user input or selected from the example dataset.
            The website prepares and processes this data to ensure it is suitable for comparison.
        </p>
        <p>
            Input validation is used to maintain data quality and ensure that only properly formatted sequences are processed.
        </p>
    </div>

    <!-- Processing -->
    <div class="section">
        <h3>Processing and Analysis</h3>
        <p>
            The core functionality of the system is to compare biological sequences and identify similarities between them.
            These comparisons generate biologically meaningful results.
        </p>
        <p>
            The results display alignment patterns and similarity relationships that can be used to support further interpretation.
        </p>
    </div>

    <!-- Front-end -->
    <div class="section">
        <h3>Front-End Design</h3>
        <p>
            The interface is designed to be clear, structured and user-friendly.
            Visual elements are arranged to present information in a readable and consistent manner.
        </p>
        <p>
            Styling is applied uniformly across the application using a global style sheet to maintain a cohesive appearance. 
            Animation and cards are implemented where relevant to improve the appearance.
        </p>
    </div>

    <!-- Navigation -->
    <div class="section">
        <h3>Navigation</h3>
        <p>
            A shared navigation bar is present on all pages to provide quick access to the main sections of the website.
            This ensures a consistent user experience throughout.
        </p>
    </div>

    <!-- Summary -->
    <div class="section">
        <h3>Summary</h3>
        <p>
            This website brings together sequence data handling, user interaction and result presentation within a structured web interface.
            It is designed to provide a clear and accessible environment for biological sequence analysis.
        </p>
    </div>

</div>

</body>
</html>
