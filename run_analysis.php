<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'login.php';

// ---------------------------
// CONFIG
// ---------------------------
$workDir = sys_get_temp_dir() . "/bioinf_tmp";
$webTmp  = __DIR__ . "/tmp";

if (!is_dir($workDir)) {
    mkdir($workDir, 0777, true);
}

if (!is_dir($webTmp)) {
    mkdir($webTmp, 0777, true);
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
function getExampleAlignment($pdo, $workDir, $webTmp) {

    $alignedFile = $webTmp . "/aligned_example.fasta";

    if (file_exists($alignedFile) && filesize($alignedFile) > 0) {
        return $alignedFile;
    }

    echo "<p>Generating example alignment (first time)...</p>";

    $stmt = $pdo->query("SELECT sequence FROM aves_g6p");
    $sequences = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $inputFile   = $workDir . "/example_input.fasta";
    $tempAligned = $workDir . "/example_aligned.fasta";

    $fh = fopen($inputFile, "w");

    foreach ($sequences as $i => $seq) {
        fwrite($fh, ">seq_" . ($i + 1) . "\n");
        fwrite($fh, wordwrap($seq, 60, "\n", true) . "\n");
    }

    fclose($fh);

    $cmd = "/usr/bin/clustalo -i " . escapeshellarg($inputFile) .
           " -o " . escapeshellarg($tempAligned) .
           " --force --threads=4 --iterations=1 2>&1";

    shell_exec($cmd);

    if (!file_exists($tempAligned)) {
        die("Error: Failed to generate example alignment.");
    }

    copy($tempAligned, $alignedFile);

    return $alignedFile;
}

// ---------------------------
// LOAD SEQUENCES
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

    $email = "s2328610@ed.ac.uk"; 

    if (empty($protein) || empty($taxon)) {
        die("Error: Protein and taxon required.");
    }

    $job_id = uniqid("job_");

    $query = urlencode("$protein AND $taxon");

    $esearch_url = "https://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi?"
                 . "db=protein&term=$query&retmax=$max_seq&retmode=json"
                 . "&email=" . urlencode($email)
                 . "&tool=protein_analysis_app";

    $esearch = json_decode(file_get_contents($esearch_url), true);
    $ids = $esearch['esearchresult']['idlist'] ?? [];

    if (empty($ids)) {
        die("Error: No sequences found.");
    }

    sleep(1);

    $efetch_url = "https://eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi?"
                . "db=protein&id=" . implode(",", $ids)
                . "&rettype=fasta&retmode=text"
                . "&email=" . urlencode($email)
                . "&tool=protein_analysis_app";

    $fasta = file_get_contents($efetch_url);

    if (!$fasta) {
        die("Error fetching FASTA.");
    }

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

    if ($seq !== "") {
        $sequences[] = $seq;
    }

    $stmt = $pdo->prepare("INSERT INTO sequences (dataset, job_id, sequence) VALUES (?, ?, ?)");

    foreach ($sequences as $s) {
        $stmt->execute([$protein, $job_id, $s]);
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
// WRITE FASTA
// ---------------------------
$inputFile = $workDir . "/input.fasta";

$fh = fopen($inputFile, "w");
foreach ($sequences as $i => $seq) {
    fwrite($fh, ">seq_" . ($i + 1) . "\n");
    fwrite($fh, wordwrap($seq, 60, "\n", true) . "\n");
}
fclose($fh);

// ---------------------------
// OUTPUT HEADER
// ---------------------------
echo "<h2>Analysis Results</h2>";
echo "<p><strong>Sequences:</strong> " . count($sequences) . "</p>";

// ---------------------------
// TRACK ALIGNMENT
// ---------------------------
$currentAlignmentFile = null;

// ---------------------------
// ANALYSES
// ---------------------------
foreach ($analyses as $analysis) {

    echo "<hr><h3>" . ucfirst($analysis) . "</h3>";

    switch ($analysis) {

        case 'alignment':

            if ($mode === 'existing' && $dataset === 'example') {
                $alignedFile = getExampleAlignment($pdo, $workDir, $webTmp);
                echo "<p>Using cached example alignment.</p>";
            } else {
                $alignedFile = $workDir . "/aligned.fasta";

                $cmd = "/usr/bin/clustalo -i " . escapeshellarg($inputFile) .
                       " -o " . escapeshellarg($alignedFile) .
                       " --force --threads=4 --iterations=1 2>&1";

                $output = shell_exec($cmd);
            }

            if (file_exists($alignedFile)) {
                $currentAlignmentFile = $alignedFile;
                echo "<pre>" . htmlspecialchars(file_get_contents($alignedFile)) . "</pre>";
            } else {
                echo "<p>Alignment failed.</p>";
                echo "<pre>$output</pre>";
            }

            break;

        case 'conservation':

            if (!$currentAlignmentFile || !file_exists($currentAlignmentFile)) {
                echo "<p>Error: alignment must be run first.</p>";
                break;
            }

            $plotTemp = $workDir . "/plotcon";
            $plotWeb  = $webTmp . "/plotcon.png";

            $cmd = "/usr/bin/plotcon -sequence " . escapeshellarg($currentAlignmentFile) .
                   " -graph png -goutfile " . escapeshellarg($plotTemp) .
                   " -winsize 4 -auto 2>&1";

            $output = shell_exec($cmd);

            $files = glob($workDir . "/plotcon*.png");

            if (!empty($files)) {
                copy($files[0], $plotWeb);
                echo "<img src='tmp/plotcon.png?" . time() . "'>";
            } else {
                echo "<p>Conservation plot failed.</p>";
                echo "<pre>$output</pre>";
            }

            break;

        case 'motifs':

            $motifTemp = $workDir . "/motifs.txt";
            $motifWeb  = $webTmp . "/motifs.txt";

            $cmd = "/usr/bin/patmatmotifs -sequence " . escapeshellarg($inputFile) .
                   " -outfile " . escapeshellarg($motifTemp) . " 2>&1";

            $output = shell_exec($cmd);

            if (file_exists($motifTemp)) {
                copy($motifTemp, $motifWeb);
                echo "<pre>" . htmlspecialchars(file_get_contents($motifWeb)) . "</pre>";
            } else {
                echo "<p>Motif scan failed.</p>";
                echo "<pre>$output</pre>";
            }

            break;

        case 'length':

            $lengths = array_map('strlen', $sequences);
            echo "<p>Average: " . round(array_sum($lengths)/count($lengths),2) . "</p>";
            echo "<p>Min: " . min($lengths) . "</p>";
            echo "<p>Max: " . max($lengths) . "</p>";
            break;
    }
}

echo "<br><br><a href='analysis_UI.php'>← Back</a>";
?>
