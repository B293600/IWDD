<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'login.php'; // must define $pdo

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
$mode     = $_POST['mode'] ?? 'existing';
$dataset  = $_POST['dataset'] ?? 'example';
$job_id   = $_POST['job_id'] ?? null;
$analyses = $_POST['analysis'] ?? [];

if (!is_array($analyses)) {
    $analyses = [$analyses];
}

$analyses = array_map('strtolower', $analyses);

// ---------------------------
// STORAGE
// ---------------------------
$inputFile = $tmpDir . "/input.fasta";
$sequences = [];

// ---------------------------
// MODE: EXISTING
// ---------------------------
if ($mode === "existing") {

    // Example dataset → aves_g6p table
    if ($dataset === "example") {

        $stmt = $pdo->prepare("SELECT sequence FROM aves_g6p");
        $stmt->execute();
        $sequences = $stmt->fetchAll(PDO::FETCH_COLUMN);

    } else {

        if (!$job_id) {
            die("Error: No job_id provided.");
        }

        $stmt = $pdo->prepare("SELECT sequence FROM sequences WHERE job_id = ?");
        $stmt->execute([$job_id]);
        $sequences = $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}

// ---------------------------
// MODE: NEW (NCBI FETCH)
// ---------------------------
if ($mode === "new") {

    $protein = $_POST['protein_query'] ?? '';
    $taxon   = $_POST['taxon_query'] ?? '';
    $max_seq = $_POST['max_seq'] ?? 10;

    // 👉 Your email (required by NCBI)
    $email = "s2328610@ed.ac.ukm";

    if (empty($protein) || empty($taxon)) {
        die("Error: Missing query parameters.");
    }

    // Generate job_id
    $job_id = uniqid("job_");

    // ---------------------------
    // STEP 1: ESEARCH
    // ---------------------------
    $query = urlencode("$protein AND $taxon");

    $esearch_url = "https://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi?"
                 . "db=protein&term=$query&retmax=$max_seq&retmode=json"
                 . "&email=" . urlencode($email);

    $esearch_response = file_get_contents($esearch_url);
    $esearch_data = json_decode($esearch_response, true);

    if (!isset($esearch_data['esearchresult']['idlist'])) {
        die("Error: No results from NCBI esearch.");
    }

    $id_list = $esearch_data['esearchresult']['idlist'];

    if (empty($id_list)) {
        die("Error: No sequence IDs found.");
    }

    // Be polite to NCBI
    sleep(1);

    // ---------------------------
    // STEP 2: EFETCH
    // ---------------------------
    $ids = implode(",", $id_list);

    $efetch_url = "https://eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi?"
                . "db=protein&id=$ids&rettype=fasta&retmode=text"
                . "&email=" . urlencode($email);

    $fasta_data = file_get_contents($efetch_url);

    if (!$fasta_data) {
        die("Error: Failed to fetch FASTA data.");
    }

    // ---------------------------
    // STEP 3: PARSE FASTA
    // ---------------------------
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

    // ---------------------------
    // STEP 4: INSERT INTO DB
    // ---------------------------
    $stmt = $pdo->prepare("INSERT INTO sequences (dataset, job_id, sequence) VALUES (?, ?, ?)");

    foreach ($sequences as $seq) {
        $stmt->execute([$protein, $job_id, $seq]);
    }

    echo "<p><strong>New dataset created from NCBI.</strong></p>";
    echo "<p><strong>Job ID:</strong> $job_id</p>";
}

// ---------------------------
// VALIDATION
// ---------------------------
if (empty($sequences)) {
    die("Error: No sequences found.");
}

// ---------------------------
// WRITE FASTA FILE
// ---------------------------
$fh = fopen($inputFile, "w");

foreach ($sequences as $i => $seq) {
    fwrite($fh, ">seq_" . ($i + 1) . "\n");
    fwrite($fh, wordwrap($seq, 60, "\n", true) . "\n");
}

fclose($fh);

// ---------------------------
// HEADER OUTPUT
// ---------------------------
echo "<h2>Analysis Results</h2>";

if ($mode === "existing") {
    echo "<p><strong>Dataset:</strong> " . htmlspecialchars($dataset) . "</p>";
    if ($job_id) {
        echo "<p><strong>Job ID:</strong> " . htmlspecialchars($job_id) . "</p>";
    }
} else {
    echo "<p><strong>Dataset:</strong> New dataset (NCBI query)</p>";
}

// ---------------------------
// LOAD FASTA
// ---------------------------
$lines = file($inputFile);

// Count sequences
$sequenceCount = 0;
foreach ($lines as $line) {
    if (strpos($line, '>') === 0) {
        $sequenceCount++;
    }
}

echo "<p><strong>Sequences used:</strong> $sequenceCount</p>";

// ---------------------------
// ANALYSES
// ---------------------------
foreach ($analyses as $analysis) {

    echo "<hr>";
    echo "<h3>" . ucfirst($analysis) . "</h3>";

    switch ($analysis) {

        case 'length':

            $lengths = [];
            $seq = "";

            foreach ($lines as $line) {
                if (strpos($line, '>') === 0) {
                    if (!empty($seq)) {
                        $lengths[] = strlen($seq);
                        $seq = "";
                    }
                } else {
                    $seq .= trim($line);
                }
            }

            if (!empty($seq)) {
                $lengths[] = strlen($seq);
            }

            if (count($lengths) > 0) {
                echo "<p>Average length: " . round(array_sum($lengths)/count($lengths), 2) . "</p>";
                echo "<p>Min: " . min($lengths) . "</p>";
                echo "<p>Max: " . max($lengths) . "</p>";
            }

            break;

        case 'alignment':

            $alignedFile = $tmpDir . "/aligned.fasta";

            $cmd = "clustalo -i $inputFile -o $alignedFile --force 2>&1";
            $output = shell_exec($cmd);

            if (file_exists($alignedFile)) {
                echo "<p>Alignment completed.</p>";
                echo "<pre>" . htmlspecialchars(file_get_contents($alignedFile)) . "</pre>";
            } else {
                echo "<p><strong>Alignment failed.</strong></p>";
                echo "<pre>$output</pre>";
            }

            break;

        case 'conservation':

            $alignedFile = $tmpDir . "/aligned.fasta";
            $plotPrefix  = $tmpDir . "/plotcon";

            if (!file_exists($alignedFile)) {
                echo "<p>Error: alignment required first.</p>";
                break;
            }

            $cmd = "plotcon -sequence $alignedFile -graph png -goutfile $plotPrefix -winsize 4 -auto 2>&1";
            $output = shell_exec($cmd);

            $possibleFiles = [
                $tmpDir . "/plotcon.png",
                $tmpDir . "/plotcon.1.png",
                $tmpDir . "/plotcon.2.png"
            ];

            $found = null;
            foreach ($possibleFiles as $file) {
                if (file_exists($file)) {
                    $found = $file;
                    break;
                }
            }

            if ($found) {
                echo "<img src='tmp/" . basename($found) . "?" . time() . "'>";
            } else {
                echo "<p>Conservation plot failed.</p>";
                echo "<pre>$output</pre>";
            }

            break;

        case 'motifs':

            $motifFile = $tmpDir . "/motifs.txt";

            $cmd = "patmatmotifs -sequence $inputFile -outfile $motifFile 2>&1";
            $output = shell_exec($cmd);

            if (file_exists($motifFile)) {
                echo "<pre>" . htmlspecialchars(file_get_contents($motifFile)) . "</pre>";
            } else {
                echo "<p>Motif scan failed.</p>";
                echo "<pre>$output</pre>";
            }

            break;

        default:
            echo "<p>No valid analysis selected: " . htmlspecialchars($analysis) . "</p>";
            break;
    }
}

// ---------------------------
// BACK LINK
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
