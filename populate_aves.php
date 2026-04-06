<?php
require_once 'login.php';

// ==========================
// CONFIGURATION
// ==========================

$email = "s2328610@ed.ac.uk";
$batchSize = 20;                 // Number of accessions per request
$delayMicroseconds = 500000;     // 0.5 sec between requests
$maxRetries = 5;

// ==========================
// DATABASE CONNECTION
// ==========================

$pdo = new PDO(
    "mysql:host=$hostname;dbname=$database;charset=utf8mb4",
    $username,
    $password
);

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// ==========================
// READ ACCESSIONS
// ==========================

$file = "data/example/aves_g6p.fasta";
$handle = fopen($file, "r");

if (!$handle) {
    die("Could not open FASTA file.");
}

$accessions = [];

while (($line = fgets($handle)) !== false) {
    if (strpos($line, ">") === 0) {
        $header = substr(trim($line), 1);
        $parts = explode(" ", $header);
        $accessions[] = $parts[0];
    }
}

fclose($handle);

// Remove duplicates just in case
$accessions = array_unique($accessions);

// ==========================
// HELPER: FETCH WITH RETRIES
// ==========================

function fetchWithRetry($url, $maxRetries) {
    for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {

        $result = @file_get_contents($url);

        if ($result !== false && strlen($result) > 0) {
            return $result;
        }

        // Exponential backoff
        $wait = pow(2, $attempt - 1) * 500000;
        usleep($wait);
    }

    return false;
}

// ==========================
// HELPER: PARSE MULTI-FASTA
// ==========================

function parseMultiFasta($fasta) {
    $entries = [];
    $chunks = preg_split('/^>/m', $fasta, -1, PREG_SPLIT_NO_EMPTY);

    foreach ($chunks as $chunk) {
        $lines = explode("\n", trim($chunk));
        $header = array_shift($lines);

        $parts = explode(" ", $header, 2);
        $accession = $parts[0];
        $description = $parts[1] ?? '';

        $sequence = implode("", array_map('trim', $lines));
        $length = strlen($sequence);

        $entries[] = [
            'accession' => $accession,
            'description' => $description,
            'length' => $length,
	    'sequence' => $sequence
        ];
    }

    return $entries;
}

// ==========================
// MAIN PROCESSING
// ==========================

$chunks = array_chunk($accessions, $batchSize);
$failed = [];

$stmt = $pdo->prepare("
    INSERT IGNORE INTO aves_g6p (accession, description, length, sequence)
    VALUES (:accession, :description, :length, :sequence)
");

foreach ($chunks as $group) {

    $idList = implode(",", $group);

    $url = "https://eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi?"
         . "db=protein"
         . "&id=" . urlencode($idList)
         . "&rettype=fasta"
         . "&retmode=text"
         . "&email=s2328610@ed.ac.uk" . urlencode($email);

    $fasta = fetchWithRetry($url, $maxRetries);

    if ($fasta === false) {
        echo "Batch failed: $idList<br>";
        $failed = array_merge($failed, $group);
        continue;
    }

    $entries = parseMultiFasta($fasta);

    // Track which ones we successfully got
    $fetchedAccessions = [];

    foreach ($entries as $entry) {
        $fetchedAccessions[] = $entry['accession'];

        try {
            $stmt->execute([
                ':accession' => $entry['accession'],
                ':description' => $entry['description'],
                ':length' => $entry['length'],
		':sequence' => $entry['sequence']
            ]);

            echo "Inserted: {$entry['accession']}<br>";

        } catch (PDOException $e) {
            echo "DB issue for {$entry['accession']}<br>";
        }
    }

    // Detect missing ones in batch
    $missing = array_diff($group, $fetchedAccessions);
    if (!empty($missing)) {
        foreach ($missing as $m) {
            echo "Missing: $m<br>";
        }
        $failed = array_merge($failed, $missing);
    }

    usleep($delayMicroseconds);
}

// ==========================
// SAVE FAILURES
// ==========================

$failed = array_unique($failed);

if (!empty($failed)) {
    file_put_contents("failed_accessions.txt", implode("\n", $failed));
    echo "<br>Saved failed accessions to failed_accessions.txt<br>";
}

// ==========================
// DONE
// ==========================

echo "<br>Done.";
?>
