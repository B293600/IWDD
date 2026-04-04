<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'login.php';

// ---------------------------
// CONFIG
// ---------------------------
$tmpDir = __DIR__ . "/tmp";

if (!is_dir($tmpDir)) {
    mkdir($tmpDir, 0777, true);
}

// ---------------------------
// INPUTS
// ---------------------------
$mode    = $_POST['mode'] ?? '';
$dataset = $_POST['dataset'] ?? null;
$job_id  = $_POST['job_id'] ?? null;

$analyses = $_POST['analysis'] ?? [];
if (!is_array($analyses)) {
    $analyses = [$analyses];
}
$analyses = array_map('strtolower', $analyses);

// ---------------------------
// FUNCTION: CACHE EXAMPLE MSA
// ---------------------------
function getExampleAlignment($pdo, $tmpDir) {

    $alignedFile = $tmpDir . "/aligned_example.fasta";

    // ✅ Use cached file if exists
    if (file_exists($alignedFile) && filesize($alignedFile) > 0) {
        return $alignedFile;
    }

    echo "<p>Generating example alignment (first time)...</p>";

    // Fetch sequences
    $stmt = $pdo->query("SELECT sequence FROM aves_g6p");
    $sequences = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (empty($sequences)) {
        die("Error: No sequences found in example dataset.");
    }

    // Write temp FASTA
    $inputFile = $tmpDir . "/example_input.fasta";
    $fh = fopen($inputFile, "w");

    foreach ($sequences as $i => $seq) {
        fwrite($fh, ">seq_" . ($i + 1) . "\n");
        fwrite($fh, wordwrap($seq, 60, "\n", true) . "\n");
    }

    fclose($fh);

    // Run fast Clustal Omega
    $cmd = "clustalo -i " . escapeshellarg($inputFile) .
           " -o " . escapeshellarg($alignedFile) .
           " --force --threads=4 --iterations=1 2>&1";

    $output = shell_exec($cmd);

    if (!file_exists($alignedFile)) {
        echo "<pre>$output</pre>";
        die("Error: Failed to generate example alignment.");
    }

    return $alignedFile;
}

// ---------------------------
// LOAD / CREATE DATASET
// ---------------------------
$sequences = [];

if ($mode === 'existing') {

    if ($dataset === 'example') {

        $stmt = $pdo->query("SELECT sequence FROM aves_g6p");
        $sequences = $stmt->fetchAll(PDO::FETCH_COLUMN);

    } elseif ($dataset === 'user') {

        if (empty($job_id)) {
            die("Error: Job ID required.");
        }

        $stmt = $pdo->prepare("SELECT sequence FROM sequences WHERE job_id = ?");
        $stmt->execute([$job_id]);
        $sequences = $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

} elseif ($mode === 'new') {

    $protein = $_POST['protein_query'] ?? '';
    $taxon   = $_POST['taxon_query'] ?? '';
    $max_seq = $_POST['max_seq'] ?? 50;

    if (empty($protein) || empty($taxon)) {
        die("Error: Protein and taxon required.");
    }

    $job_id = uniqid("job_");

    $query = urlencode("$protein AND $taxon");

    $esearch_url = "https://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi?"
        . "db=protein&term=$query&retmax=$max_seq&retmode=json";

    $esearch_data = json_decode(file_get_contents($esearch_url), true);
    $id_list = $esearch_data['esearchresult']['idlist'] ?? [];

    if (empty($id_list)) {
        die("Error: No sequences found.");
    }

    sleep(1);

    $ids = implode(",", $id_list);

    $efetch_url = "https://eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi?"
        . "db=protein&id=$ids&rettype=fasta&retmode=text";

    $fasta_data = file_get_contents($efetch_url);

    $lines = explode("\n", trim($fasta_data));
    $current_seq = "";

    foreach ($lines as $line) {
        if (strpos($line, ">") === 0) {
            if (!empty($current_seq)) {
                $sequences[] = $current_seq;
                $current_seq = "";
            }
        } else {
            $current_seq .= trim($line);
        }
    }

    if (!empty($current_seq)) {
        $sequences[] = $current_seq;
    }

    // Store
    $stmt = $pdo->prepare("INSERT INTO sequences (dataset, job_id, sequence) VALUES (?, ?, ?)");

    foreach ($sequences as $seq) {
        $stmt->execute([$protein, $job_id, $seq]);
    }

    echo "<p><strong>New dataset created.</strong></p>";
    echo "<p><strong>Job ID:</strong> $job_id</p>";
}

// ---------------------------
// VALIDATION
// ---------------------------
if (empty($sequences)) {
    die("Error: No sequences available.");
}

// ---------------------------
// WRITE FASTA (for non-example datasets)
// ---------------------------
$inputFile = $tmpDir . "/input.fasta";

$fh = fopen($inputFile, "w");
foreach ($sequences as $i => $seq) {
    fwrite($fh, ">seq_" . ($i + 1) . "\n");
    fwrite($fh, wordwrap($seq, 60, "\n", true) . "\n");
}
fclose($fh);

echo "<h2>Analysis Results</h2>";
echo "<p><strong>Sequences used:</strong> " . count($sequences) . "</p>";

// ---------------------------
// ANALYSES
// ---------------------------
foreach ($analyses as $analysis) {

    echo "<hr>";
    echo "<h3>" . ucfirst($analysis) . "</h3>";

    switch ($analysis) {

        case 'alignment':

            if ($mode === 'existing' && $dataset === 'example') {

                // ✅ USE CACHED MSA
                $alignedFile = getExampleAlignment($pdo, $tmpDir);
                echo "<p>Using cached example alignment.</p>";

            } else {

                // 🔥 NORMAL MSA
                $alignedFile = $tmpDir . "/aligned.fasta";

                $cmd = "clustalo -i " . escapeshellarg($inputFile) .
                       " -o " . escapeshellarg($alignedFile) .
                       " --force --threads=4 --iterations=1 2>&1";

                $output = shell_exec($cmd);
            }

            if (file_exists($alignedFile)) {
                echo "<pre>" . htmlspecialchars(file_get_contents($alignedFile)) . "</pre>";
            } else {
                echo "<p>Alignment failed.</p>";
            }

            break;

        // (keep your other analyses unchanged)

        default:
            echo "<p>No valid analysis selected: " . htmlspecialchars($analysis) . "</p>";
    }
}

// ---------------------------
// BACK BUTTON
// ---------------------------
echo "
<br><br>
<style>
.back-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 18px;
    background-color: #6A1FD1;
    color: #ffffff;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    box-shadow: 0 4px 10px rgba(106, 31, 209, 0.35);
    transition: all 0.25s ease;
}

.back-btn:hover {
    background-color: #5518A8;
    transform: translateY(-2px);
    box-shadow: 0 6px 14px rgba(106, 31, 209, 0.45);
}

.back-btn:active {
    transform: translateY(0px);
    box-shadow: 0 3px 8px rgba(106, 31, 209, 0.3);
}
</style>

<a href='analysis_UI.php' class='back-btn'>
    ← Back to Analysis
</a>
";
?>
