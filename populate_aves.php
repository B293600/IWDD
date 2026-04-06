<?php
// Include database login credentials
require_once 'login.php';

// CONFIGURATION
// Email required by NCBI API (used for identification)
$email = "s2328610@ed.ac.uk";

// Number of accession IDs to fetch per API request
$batchSize = 20;

// Delay between API requests to avoid rate limiting
$delayMicroseconds = 500000;     

// Maximum number of retry attempts for failed API requests
$maxRetries = 5;

// DATABASE CONNECTION
// Create PDO connection using credentials from login.php
$pdo = new PDO(
    "mysql:host=$hostname;dbname=$database;charset=utf8mb4",
    $username,
    $password
);

// Enable exceptions for database errors
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// READ ACCESSIONS FROM FASTA FILE
// Path to FASTA file containing sequence data
$file = "data/example/aves_g6p.fasta";

// Open file for reading
$handle = fopen($file, "r");

// Stop execution if file cannot be opened
if (!$handle) {
    die("Could not open FASTA file.");
}

// Array to store extracted accession IDs
$accessions = [];

// Read file line by line
while (($line = fgets($handle)) !== false) {

    // Check if line is a FASTA header
    if (strpos($line, ">") === 0) {

        // Remove ">" and trim whitespace
        $header = substr(trim($line), 1);

        // Split header to extract accession ID
        $parts = explode(" ", $header);

        // Store accession (first part of header)
        $accessions[] = $parts[0];
    }
}

// Close file after reading
fclose($handle);

// Remove duplicate accession IDs
$accessions = array_unique($accessions);

// FETCH DATA WITH RETRIES
// Attempts to fetch data from a URL with retry logic
function fetchWithRetry($url, $maxRetries) {
    for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {

        // Suppress warnings and attempt request
        $result = @file_get_contents($url);

        // Return result if successful
        if ($result !== false && strlen($result) > 0) {
            return $result;
        }

        // Exponential backoff before retrying
        $wait = pow(2, $attempt - 1) * 500000;
        usleep($wait);
    }

    // Return false if all attempts fail
    return false;
}

// PARSE MULTI-FASTA DATA
// Converts FASTA text into structured array entries
function parseMultiFasta($fasta) {

    $entries = [];

    // Split FASTA into individual entries
    $chunks = preg_split('/^>/m', $fasta, -1, PREG_SPLIT_NO_EMPTY);

    foreach ($chunks as $chunk) {

        // Split each entry into lines
        $lines = explode("\n", trim($chunk));

        // First line is the header
        $header = array_shift($lines);

        // Extract accession and description
        $parts = explode(" ", $header, 2);
        $accession = $parts[0];
        $description = $parts[1] ?? '';

        // Combine sequence lines into one string
        $sequence = implode("", array_map('trim', $lines));

        // Calculate sequence length
        $length = strlen($sequence);

        // Store parsed data
        $entries[] = [
            'accession' => $accession,
            'description' => $description,
            'length' => $length,
            'sequence' => $sequence
        ];
    }

    return $entries;
}

// MAIN PROCESSING
// Split accessions into batches
$chunks = array_chunk($accessions, $batchSize);

// Store failed accession IDs
$failed = [];

// Prepare SQL statement to insert data (ignores duplicates)
$stmt = $pdo->prepare("
    INSERT IGNORE INTO aves_g6p (accession, description, length, sequence)
    VALUES (:accession, :description, :length, :sequence)
");

// Process each batch
foreach ($chunks as $group) {

    // Convert batch into comma-separated list
    $idList = implode(",", $group);

    // Build NCBI API request URL
    $url = "https://eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi?"
         . "db=protein"
         . "&id=" . urlencode($idList)
         . "&rettype=fasta"
         . "&retmode=text"
         . "&email=" . urlencode($email);

    // Fetch FASTA data with retry logic
    $fasta = fetchWithRetry($url, $maxRetries);

    // Handle failed batch request
    if ($fasta === false) {
        echo "Batch failed: $idList<br>";
        $failed = array_merge($failed, $group);
        continue;
    }

    // Parse returned FASTA data
    $entries = parseMultiFasta($fasta);

    // Track successfully fetched accessions
    $fetchedAccessions = [];

    // Insert each entry into database
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

    // Identify missing accessions not returned by API
    $missing = array_diff($group, $fetchedAccessions);

    if (!empty($missing)) {
        foreach ($missing as $m) {
            echo "Missing: $m<br>";
        }
        $failed = array_merge($failed, $missing);
    }

    // Delay before next request to avoid API overload
    usleep($delayMicroseconds);
}

// SAVE FAILED ACCESSIONS
// Remove duplicate failures
$failed = array_unique($failed);

// Save failed accession IDs to file
if (!empty($failed)) {
    file_put_contents("failed_accessions.txt", implode("\n", $failed));
    echo "<br>Saved failed accessions to failed_accessions.txt<br>";
}

// COMPLETION MESSAGE
// Script has finished running
echo "<br>Done.";
?>
