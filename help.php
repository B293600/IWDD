<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Help / Context</title>

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

<!-- Navigation bar -->
<div class="navbar">
    <a href="index.php">Home</a>
    <a href="aves.php">Example Dataset</a>
    <a href="analysis_UI.php">Input Page</a>
    <a href="credits.php">Credit Page</a>
    <a href="about.php">About</a>
    <a href="help.php">Help</a>
</div>

<div class="container">

    <h2>Help and Biological Context</h2>

    <!-- Overview -->
    <div class="section">
        <h3>Overview</h3>
        <p>
            This website is designed to support biological sequence analysis, particularly for understanding relationships between protein or nucleotide sequences.
            It allows users to explore similarities between sequences and to gain insight into potential functional, structural, or evolutionary relationships.
        </p>
    </div>

    <!-- Biological rationale -->
    <div class="section">
        <h3>Biological Rationale</h3>
        <p>
            Sequence comparison is a fundamental technique in molecular biology and bioinformatics.
            Sequences that are highly similar often have a common evolutionary origin and may perform similar biological functions.
        </p>

        <p>By comparing sequences, you can:</p>

        <ul>
            <li>Identify homologous proteins or genes</li>
            <li>Infer evolutionary relationships between organisms</li>
            <li>Predict potential function of uncharacterised sequences</li>
            <li>Detect conserved regions important for structure or activity</li>
        </ul>
    </div>

    <!-- Interpretation -->
    <div class="section">
        <h3>Interpreting Results</h3>

        <p>
            Similarity results should be interpreted in a biological context rather than as absolute numerical values.
        </p>

        <ul>
            <li><strong>High similarity:</strong> Suggests strong evolutionary conservation and possible shared function.</li>
            <li><strong>Moderate similarity:</strong> May indicate shared domains or partial conservation.</li>
            <li><strong>Low similarity:</strong> Suggests distant or no evolutionary relationship.</li>
        </ul>

        <p>
            Conserved regions within sequences are often biologically significant and may correspond to functional domains, binding sites, or structurally important residues.
        </p>
    </div>

    <!-- Example dataset -->
    <div class="section">
        <h3>Example Dataset</h3>
        <p>
            The example dataset provides biological sequences that show how similarity varies across related and unrelated sequences.
            It is intended to support understanding of patterns commonly observed in real biological data.
        </p>
    </div>

    <!-- Limitations -->
    <div class="section">
        <h3>Limitations</h3>
        <p>
            Sequence similarity alone does not guarantee identical biological function. Interpretation should consider additional biological context such as:
        </p>

        <ul>
            <li>Experimental validation</li>
            <li>Protein domain structure</li>
            <li>Species and evolutionary background</li>
            <li>Post-translational modifications</li>
        </ul>
    </div>

    <!-- Usage -->
    <div class="section">
        <h3>How to Use This Tool</h3>

        <ul>
            <li>Select or search for biological sequences</li>
            <li>Submit the sequences for comparison</li>
            <li>Review similarity results and alignments</li>
            <li>Use the output to support biological interpretation</li>
        </ul>
    </div>

    <!-- Summary -->
    <div class="section">
        <h3>Summary</h3>
        <p>
            This tool explores biological sequence relationships by highlighting similarity patterns that may reflect shared ancestry or functional characteristics.
            It is intended as an aid for analysis and for hypothesis generation within biological research.
        </p>
    </div>

</div>

</body>
</html>
