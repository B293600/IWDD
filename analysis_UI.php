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

<form action="run_analysis.php" method="POST">

    <!-- Mode Selection -->
    <h3>Select Mode</h3>
    <select name="mode" id="modeSelect" onchange="toggleMode()" required>
        <option value="existing">Use Existing Dataset</option>
        <option value="new">Create New Dataset (NCBI Query)</option>
    </select>

    <br><br>

    <!-- ================= EXISTING DATASET ================= -->
    <div id="existingSection">

        <h3>Select Existing Dataset</h3>

        <select name="dataset">
            <option value="example">Example Dataset (Glucose-6-phosphatase proteins from Aves)</option>
            <option value="user">User Dataset (enter Job ID)</option>
        </select>

        <div id="jobInput" style="display:none; margin-top:10px;">
            <br>
            Enter Job ID:
            <input type="text" name="job_id" placeholder="e.g. 12">
        </div>

    </div>

    <!-- ================= NEW DATASET ================= -->
    <div id="newSection" style="display:none;">

        <h3>Create New Dataset (NCBI Query)</h3>

        Protein family / keyword:<br>
        <input type="text" name="protein_query" placeholder="e.g. kinase"><br><br>

        Taxonomic group:<br>
        <input type="text" name="taxon_query" placeholder="e.g. Mammalia, Aves, Rodentia"><br><br>

        Max sequences:<br>
        <input type="number" name="max_seq" value="50"><br><br>

    </div>

    <br>

    <!-- ================= ANALYSIS OPTIONS ================= -->
    <h3>Select Analyses (you can choose multiple)</h3>

    <label>
        <input type="checkbox" name="analysis[]" value="length" checked>
        Length Statistics
    </label>
    <br>

    <label>
        <input type="checkbox" name="analysis[]" value="alignment">
        Multiple Sequence Alignment
    </label>
    <br>

    <label>
        <input type="checkbox" name="analysis[]" value="conservation">
        Conservation Plot
    </label>
    <br>

    <label>
        <input type="checkbox" name="analysis[]" value="motifs">
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

    // Reset job input visibility when switching
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
