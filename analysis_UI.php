<?php
session_start();

$saved = $_SESSION['analysis_form'] ?? [];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Protein Sequence Analysis</title>
    <link rel="stylesheet" href="style.css">
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

<h2>Protein Sequence Analysis</h2>

<form action="loading.php" method="POST">

    <!-- Mode Selection -->
    <h3>Select Mode</h3>
    <select name="mode" id="modeSelect" onchange="toggleMode()" required>
        <option value="existing" <?= ($saved['mode'] ?? 'existing') === 'existing' ? 'selected' : '' ?>>
            Use Existing Dataset
        </option>
        <option value="new" <?= ($saved['mode'] ?? '') === 'new' ? 'selected' : '' ?>>
            Create New Dataset (NCBI Query)
        </option>
    </select>

    <br><br>

    <!-- ================= EXISTING DATASET ================= -->
    <div id="existingSection">

        <h3>Select Existing Dataset</h3>

        <select name="dataset">
            <option value="example" <?= ($saved['dataset'] ?? '') === 'example' ? 'selected' : '' ?>>
                Example Dataset (Glucose-6-phosphatase proteins from Aves)
            </option>
            <option value="user" <?= ($saved['dataset'] ?? '') === 'user' ? 'selected' : '' ?>>
                User Dataset (enter Job ID)
            </option>
        </select>

        <div id="jobInput" style="display: <?= ($saved['dataset'] ?? '') === 'user' ? 'block' : 'none' ?>; margin-top:10px;">
            <br>
            Enter Job ID:
            <input type="text" name="job_id"
                   value="<?= htmlspecialchars($saved['job_id'] ?? '') ?>"
                   placeholder="e.g. 12">
        </div>

    </div>

    <!-- ================= NEW DATASET ================= -->
    <div id="newSection" style="display: <?= ($saved['mode'] ?? '') === 'new' ? 'block' : 'none' ?>;">

        <h3>Create New Dataset (NCBI Query)</h3>

        Protein family / keyword:<br>
        <input type="text" name="protein_query"
               value="<?= htmlspecialchars($saved['protein_query'] ?? '') ?>"
               placeholder="e.g. kinase"><br><br>

        Taxonomic group:<br>
        <input type="text" name="taxon_query"
               value="<?= htmlspecialchars($saved['taxon_query'] ?? '') ?>"
               placeholder="e.g. Mammalia"><br><br>

        Max sequences: nb: Increasing this may drastically increase loading time <br>
        <input type="number" name="max_seq"
               value="<?= htmlspecialchars($saved['max_seq'] ?? '50') ?>"><br><br>

    </div>

    <br>

    <!-- ================= ANALYSIS OPTIONS ================= -->
    <h3>Select Analyses (you can choose multiple)</h3>

    <?php $savedAnalyses = $saved['analysis'] ?? []; ?>

    <label>
        <input type="checkbox" name="analysis[]" value="length"
            <?= in_array('length', $savedAnalyses) ? 'checked' : '' ?>>
        Length Statistics
    </label>
    <br>

    <label>
        <input type="checkbox" name="analysis[]" value="alignment"
            <?= in_array('alignment', $savedAnalyses) ? 'checked' : '' ?>>
        Multiple Sequence Alignment
    </label>
    <br>

    <label>
        <input type="checkbox" name="analysis[]" value="conservation"
            <?= in_array('conservation', $savedAnalyses) ? 'checked' : '' ?>>
        Conservation Plot
    </label>
    <br>

    <label>
        <input type="checkbox" name="analysis[]" value="motifs"
            <?= in_array('motifs', $savedAnalyses) ? 'checked' : '' ?>>
        Motif Scan (PROSITE-style via EMBOSS)
    </label>
    <br>

    <br>

    <button type="submit">Run Analysis</button>

</form>

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

// Show job ID input only when "user dataset" is selected
document.querySelector('select[name="dataset"]').addEventListener("change", function() {
    var jobInput = document.getElementById("jobInput");
    if (this.value === "user") {
        jobInput.style.display = "block";
    } else {
        jobInput.style.display = "none";
    }
});
</script>

</body>
</html>
