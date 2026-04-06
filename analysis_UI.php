<?php
session_start();

$saved = $_SESSION['analysis_form'] ?? [];
$mode = $saved['mode'] ?? 'existing'; // default mode
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Protein Sequence Analysis</title>

    <!-- Global stylesheet -->
    <link rel="stylesheet" href="style_sheet.css">

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f9;
        }

        .container {
            display: flex;
            justify-content: center;
            padding: 40px;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
            width: 700px;
        }

        h2, h3 {
            text-align: center;
        }

        form {
            margin-top: 20px;
        }

        input, select, button {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            margin-bottom: 15px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        button {
            cursor: pointer;
            transition: 0.3s;
            font-weight: bold;
        }

        button:hover {
            transform: scale(1.03);
        }

        .section {
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin: 5px 0;
        }

        input[type="checkbox"] {
            width: auto;
            margin-right: 8px;
        }

        .inline-group {
            margin-bottom: 15px;
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
<div class="card">

<h2>Protein Sequence Analysis</h2>

<form action="loading.php" method="POST">

    <!-- Mode Selection -->
    <div class="section">
        <h3>Select Mode</h3>
        <select name="mode" id="modeSelect" onchange="toggleMode()" required>
            <option value="existing" <?= $mode === 'existing' ? 'selected' : '' ?>>
                Use Existing Dataset
            </option>
            <option value="new" <?= $mode === 'new' ? 'selected' : '' ?>>
                Create New Dataset (NCBI Query)
            </option>
        </select>
    </div>

    <!-- Existing Dataset -->
    <div id="existingSection" class="section"
         style="display: <?= $mode === 'existing' ? 'block' : 'none' ?>;">

        <h3>Select Existing Dataset</h3>

        <select name="dataset">
            <option value="example" <?= ($saved['dataset'] ?? '') === 'example' ? 'selected' : '' ?>>
                Example Dataset (Glucose-6-phosphatase proteins from Aves)
            </option>
            <option value="user" <?= ($saved['dataset'] ?? '') === 'user' ? 'selected' : '' ?>>
                User Dataset (enter Job ID)
            </option>
        </select>

        <div id="jobInput" style="display: <?= ($saved['dataset'] ?? '') === 'user' ? 'block' : 'none' ?>;">
            <input type="text" name="job_id"
                   value="<?= htmlspecialchars($saved['job_id'] ?? '') ?>"
                   placeholder="Enter Job ID (e.g. 12)">
        </div>

    </div>

    <!-- New Dataset -->
    <div id="newSection" class="section"
         style="display: <?= $mode === 'new' ? 'block' : 'none' ?>;">

        <h3>Create New Dataset (NCBI Query)</h3>

        <div class="inline-group">
            <label>Protein family / keyword:</label>
            <input type="text" name="protein_query"
                   value="<?= htmlspecialchars($saved['protein_query'] ?? '') ?>"
                   placeholder="e.g. kinase">
        </div>

        <div class="inline-group">
            <label>Taxonomic group:</label>
            <input type="text" name="taxon_query"
                   value="<?= htmlspecialchars($saved['taxon_query'] ?? '') ?>"
                   placeholder="e.g. Mammalia">
        </div>

        <div class="inline-group">
            <label>Max sequences (increasing may increase loading time):</label>
            <input type="number" name="max_seq"
                   value="<?= htmlspecialchars($saved['max_seq'] ?? '50') ?>">
        </div>

    </div>

    <!-- Analysis Options -->
    <div class="section">
        <h3>Select Analyses</h3>

        <?php $savedAnalyses = $saved['analysis'] ?? []; ?>

        <label>
            <input type="checkbox" name="analysis[]" value="length"
                <?= in_array('length', $savedAnalyses) ? 'checked' : '' ?>>
            Length Statistics
        </label>

        <label>
            <input type="checkbox" name="analysis[]" value="alignment"
                <?= in_array('alignment', $savedAnalyses) ? 'checked' : '' ?>>
            Multiple Sequence Alignment
        </label>

        <label>
            <input type="checkbox" name="analysis[]" value="conservation"
                <?= in_array('conservation', $savedAnalyses) ? 'checked' : '' ?>>
            Conservation Plot
        </label>

        <label>
            <input type="checkbox" name="analysis[]" value="motifs"
                <?= in_array('motifs', $savedAnalyses) ? 'checked' : '' ?>>
            Motif Scan (PROSITE-style via EMBOSS)
        </label>
    </div>

    <button type="submit" class="btn">Run Analysis</button>

</form>

</div>
</div>

<script>
function toggleMode() {
    var mode = document.getElementById("modeSelect").value;
    var existingSection = document.getElementById("existingSection");
    var newSection = document.getElementById("newSection");
    var jobInput = document.getElementById("jobInput");

    if (mode === "existing") {
        existingSection.style.display = "block";
        newSection.style.display = "none";
    } else {
        existingSection.style.display = "none";
        newSection.style.display = "block";
    }

    jobInput.style.display = "none";
}

// Toggle job input
document.querySelector('select[name="dataset"]').addEventListener("change", function() {
    var jobInput = document.getElementById("jobInput");
    jobInput.style.display = (this.value === "user") ? "block" : "none";
});
</script>

</body>
</html>
