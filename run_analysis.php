<?php
// Start session handling for storing user inputs across requests
session_start();

// Enable full error reporting for debugging purposes
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection credentials and PDO setup
require_once 'login.php';

// ---------------------------
// STORE LAST INPUTS IN SESSION
// ---------------------------
// Save POST data so the form can be repopulated later
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['analysis_form'] = $_POST;
}

// ---------------------------
// CONFIGURATION
// ---------------------------
// Define working directories for temporary files
$workDir = sys_get_temp_dir() . "/bioinf_tmp";
$webTmp  = __DIR__ . "/tmp";

// Create directories if they do not already exist
if (!is_dir($workDir)) mkdir($workDir, 0777, true);
if (!is_dir($webTmp)) mkdir($webTmp, 0777, true);

// ---------------------------
// INPUT PARAMETERS
// ---------------------------
// Retrieve form inputs with fallback defaults
$mode    = $_POST['mode'] ?? '';
$dataset = $_POST['dataset'] ?? null;
$job_id  = $_POST['job_id'] ?? null;

// Retrieve selected analyses and ensure array format
$analyses = $_POST['analysis'] ?? [];
if (!is_array($analyses)) $analyses = [$analyses];
$analyses = array_map('strtolower', $analyses);

// ---------------------------
// UI FLAGS
// ---------------------------
$newDatasetCreated = false;
$newJobId = null;

// ---------------------------
// LOAD SEQUENCES
// ---------------------------
// Initialise sequence storage
$sequences = [];

// Handle existing dataset mode
if ($mode === 'existing') {

    // Load example dataset from database
    if ($dataset === 'example') {
        $stmt = $pdo->query("SELECT sequence FROM aves_g6p");
        $sequences = $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    // Load user dataset using job ID
    elseif ($dataset === 'user') {

        // Validate job ID input
        if (empty($job_id)) die("Error: Job ID required.");

        $stmt = $pdo->prepare("SELECT sequence FROM sequences WHERE job_id = ?");
        $stmt->execute([$job_id]);
        $sequences = $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

} elseif ($mode === 'new') {

    // Retrieve query parameters for new dataset
    $protein = $_POST['protein_query'] ?? '';
    $taxon   = $_POST['taxon_query'] ?? '';
    $max_seq = $_POST['max_seq'] ?? 50;

    // Validate required inputs
    if (empty($protein) || empty($taxon)) {
        die("Error: Protein and taxon required.");
    }

    // Generate unique job identifier
    $job_id = uniqid("job_");
    $newJobId = $job_id;
    $newDatasetCreated = true;

    // Build NCBI query string
    $query = urlencode("$protein AND $taxon");

    // Construct ESearch URL
    $esearch_url = "https://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi?"
        . "db=protein&term=$query&retmax=$max_seq&retmode=json";

    // Fetch search results
    $esearch = json_decode(file_get_contents($esearch_url), true);
    $ids = $esearch['esearchresult']['idlist'] ?? [];

    // Ensure sequences were found
    if (empty($ids)) die("Error: No sequences found.");

    // Construct EFetch URL to retrieve FASTA sequences
    $efetch_url = "https://eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi?"
        . "db=protein&id=" . implode(",", $ids)
        . "&rettype=fasta&retmode=text";

    // Retrieve FASTA data
    $fasta = file_get_contents($efetch_url);
    if (!$fasta) die("Error fetching FASTA.");

    // Parse FASTA into individual sequences
    $lines = explode("\n", $fasta);
    $seq = "";

    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === "") continue;

        if ($line[0] === ">") {
            if ($seq !== "") {
                $sequences[] = $seq;
                $seq = "";
            }
        } else {
            $seq .= $line;
        }
    }

    if ($seq !== "") $sequences[] = $seq;

    // Store sequences in database
    $stmt = $pdo->prepare("INSERT INTO sequences (dataset, job_id, sequence) VALUES (?, ?, ?)");
    foreach ($sequences as $s) {
        $stmt->execute([$protein, $job_id, $s]);
    }
}

// ---------------------------
// VALIDATION
// ---------------------------
// Ensure sequences exist before proceeding
if (empty($sequences)) die("Error: No sequences available.");

// ---------------------------
// WRITE FASTA INPUT FILE
// ---------------------------
// Create input FASTA file for downstream tools
$inputFile = $workDir . "/input.fasta";

$fh = fopen($inputFile, "w");
foreach ($sequences as $i => $seq) {
    fwrite($fh, ">seq_" . ($i + 1) . "\n");
    fwrite($fh, wordwrap($seq, 60, "\n", true) . "\n");
}
fclose($fh);

// Verify file creation
if (!file_exists($inputFile)) {
    die("Error: Failed to create input FASTA.");
}

// ---------------------------
// ALIGNMENT FUNCTION
// ---------------------------
// Generates or retrieves a cached alignment for example dataset
function getExampleAlignment($pdo, $workDir, $webTmp) {

    $alignedFile = $webTmp . "/aligned_example.fasta";

    // Return cached alignment if available
    if (file_exists($alignedFile) && filesize($alignedFile) > 0) {
        return $alignedFile;
    }

    // Fetch example sequences from database
    $stmt = $pdo->query("SELECT sequence FROM aves_g6p");
    $sequences = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $inputFile   = $workDir . "/example_input.fasta";
    $tempAligned = $workDir . "/example_aligned.fasta";

    // Write FASTA input
    $fh = fopen($inputFile, "w");

    foreach ($sequences as $i => $seq) {
        fwrite($fh, ">seq_" . ($i + 1) . "\n");
        fwrite($fh, wordwrap($seq, 60, "\n", true) . "\n");
    }

    fclose($fh);

    // Run Clustal Omega alignment
    $cmd = "/usr/bin/clustalo -i " . escapeshellarg($inputFile) .
           " -o " . escapeshellarg($tempAligned) .
           " --force --threads=4 --iterations=1 2>&1";

    shell_exec($cmd);

    // Validate output
    if (!file_exists($tempAligned)) {
        die("Error: Failed to generate example alignment.");
    }

    // Copy to web-accessible directory
    copy($tempAligned, $alignedFile);

    return $alignedFile;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<!-- Page metadata and title -->
<meta charset="UTF-8">
<title>Analysis Results</title>

<!-- Page styling for layout, headings, buttons, and output formatting -->
<style>
body {
    font-family: Arial;
    margin: 0;
    background: linear-gradient(135deg, #f8fafc, #e2e8f0);
    color: #1f2937;
}

.container {
    max-width:1100px;
    margin:40px auto;
    background: #ffffff;
    padding:35px;
    border-radius:12px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
}

h2 {
    color: #4f46e5;
    border-bottom: 2px solid #c7d2fe;
    padding-bottom: 8px;
}

h3 {
    color: #4338ca;
    margin-top: 25px;
    border-left: 4px solid #6366f1;
    padding-left: 10px;
}

pre {
    background: #f1f5f9;
    color: #111827;
    padding: 15px;
    border-radius: 8px;
    overflow-x: auto;
    border-left: 4px solid #6366f1;
}

.button-group {
    margin: 20px 0;
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.nav-btn, .copy-btn, .back-btn {
    padding:10px 16px;
    background:#4f46e5;
    color:white;
    border-radius:8px;
    text-decoration:none;
    border:none;
    cursor:pointer;
}

.nav-btn:hover, .copy-btn:hover, .back-btn:hover {
    background:#4338ca;
}

.back-to-top {
    position:fixed;
    bottom:25px;
    right:25px;
    background:#4f46e5;
    color:white;
    padding:22px 28px;
    border-radius:999px;
    text-decoration:none;
    font-size:22px;
    font-weight:700;
    box-shadow:0 6px 16px rgba(0,0,0,0.15);
}

.back-to-top:hover {
    background:#4338ca;
    transform: translateY(-3px);
}
</style>

<script>
// Copy Job ID to clipboard
function copyJobId(id) {
    navigator.clipboard.writeText(id);
    alert("Job ID copied!");
}
</script>

</head>

<body>

<!-- Main container for results -->
<div class="container">

<h2 id="top">Analysis Results</h2>
<p><strong>Sequences:</strong> <?= count($sequences) ?></p>

<?php if ($newDatasetCreated): ?>
    <p><strong>New dataset created.</strong></p>
    <p>
        <strong>Job ID:</strong> <?= htmlspecialchars($newJobId) ?>
        <button class="copy-btn" onclick="copyJobId('<?= htmlspecialchars($newJobId) ?>')">
            Copy Job ID
        </button>
    </p>
<?php endif; ?>

<a href="analysis_UI.php" class="back-btn">← Back</a>

<div class="button-group">
<?php foreach ($analyses as $analysis): ?>
    <a href="#<?= $analysis ?>" class="nav-btn"><?= ucfirst($analysis) ?></a>
<?php endforeach; ?>
</div>

<?php
// Iterate through selected analyses and execute each one

$currentAlignmentFile = null;

foreach ($analyses as $analysis) {

    echo "<hr id='$analysis'><h3>" . ucfirst($analysis) . "</h3>";

    switch ($analysis) {

        case 'alignment':

            // Use cached example alignment if applicable
            if ($mode === 'existing' && $dataset === 'example') {
                $alignedFile = getExampleAlignment($pdo, $workDir, $webTmp);
                echo "<p>Using cached example alignment.</p>";
            } else {
                $alignedFile = $workDir . "/aligned.fasta";

                // Run Clustal Omega alignment
                $cmd = "/usr/bin/clustalo -i " . escapeshellarg($inputFile) .
                       " -o " . escapeshellarg($alignedFile) .
                       " --force --threads=4 --iterations=1 2>&1";

                $output = shell_exec($cmd);

                // Validate alignment output
                if (!file_exists($alignedFile)) {
                    echo "<p>Alignment failed.</p><pre>$output</pre>";
                    break;
                }
            }

            $currentAlignmentFile = $alignedFile;
            echo "<pre>" . htmlspecialchars(file_get_contents($alignedFile)) . "</pre>";
            break;

        case 'conservation':

            // Ensure alignment exists before plotting conservation
            if (!$currentAlignmentFile || !file_exists($currentAlignmentFile)) {
                echo "<p>Error: Alignment must be run first.</p>";
                break;
            }

            $plotTemp = $workDir . "/plotcon";
            $plotWeb  = $webTmp . "/plotcon.png";

            // Generate conservation plot using EMBOSS tool
            $cmd = "/usr/bin/plotcon -sequence " . escapeshellarg($currentAlignmentFile) .
                   " -graph png -goutfile " . escapeshellarg($plotTemp) .
                   " -winsize 4 -auto 2>&1";

            shell_exec($cmd);

            $files = glob($workDir . "/plotcon*.png");

            if (!empty($files)) {
                copy($files[0], $plotWeb);
                echo "<img src='tmp/plotcon.png?" . time() . "'>";
            } else {
                echo "<p>Conservation plot failed.</p>";
            }

            break;

        case 'motifs':

            // Run motif scanning tool
            $motifTemp = $workDir . "/motifs.txt";
            $motifWeb  = $webTmp . "/motifs.txt";

            $cmd = "/usr/bin/patmatmotifs -sequence " . escapeshellarg($inputFile) .
                   " -outfile " . escapeshellarg($motifTemp) . " 2>&1";

            shell_exec($cmd);

            if (file_exists($motifTemp)) {
                copy($motifTemp, $motifWeb);
                echo "<pre>" . htmlspecialchars(file_get_contents($motifWeb)) . "</pre>";
            } else {
                echo "<p>Motif scan failed.</p>";
            }

            break;

        case 'length':

            // Compute sequence length statistics
            $lengths = array_map('strlen', $sequences);
            echo "<p>Average: " . round(array_sum($lengths)/count($lengths),2) . "</p>";
            echo "<p>Min: " . min($lengths) . "</p>";
            echo "<p>Max: " . max($lengths) . "</p>";
            break;
    }
}
?>

<br><br>
<a href="analysis_UI.php" class="back-btn">← Back</a>

<a href="#top" class="back-to-top">↑ Top</a>

</div>

</body>
</html>
