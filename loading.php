<!DOCTYPE html>
<html>
<head>
    <title>Processing...</title>

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
            align-items: center;
            height: 80vh;
        }

        .card {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
            width: 520px;
            text-align: center;
        }

        h2 {
            margin-bottom: 10px;
        }

        .subtitle {
            color: #666;
            font-size: 14px;
            margin-bottom: 20px;
        }

        /* Progress bar */
        .progress-bar {
            width: 100%;
            height: 8px;
            background: #e5e7eb;
            border-radius: 5px;
            overflow: hidden;
            margin: 20px 0;
        }

        .progress-bar-inner {
            height: 100%;
            width: 0%;
            background: #6A1FD1;
            transition: width 0.5s ease;
        }

        /* Steps */
        .steps {
            text-align: left;
            margin-top: 20px;
        }

        .step {
            display: flex;
            align-items: center;
            padding: 10px 0;
            color: #9ca3af;
            transition: all 0.4s ease;
        }

        .step.active {
            color: #6A1FD1;
            font-weight: bold;
        }

        .step.completed {
            color: #4b5563;
        }

        .dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 12px;
            background: #d1d5db;
            transition: all 0.4s ease;
        }

        .step.active .dot {
            background: #6A1FD1;
            animation: pulse 1s infinite;
        }

        .step.completed .dot {
            background: #6A1FD1;
        }

        @keyframes pulse {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.3); opacity: 0.6; }
            100% { transform: scale(1); opacity: 1; }
        }

        /* Spinner */
        .spinner {
            margin: 15px auto;
            width: 35px;
            height: 35px;
            border: 4px solid #e5e7eb;
            border-top: 4px solid #6A1FD1;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* ✅ Force purple button styling to match run_analysis.php */
        .cancel-btn {
            margin-top: 20px;

            /* override any grey/default styling */
            background-color: #4f46e5 !important;
            color: white !important;

            padding: 10px 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;

            font-weight: 500;
            text-decoration: none;
            display: inline-block;
        }

        .cancel-btn:hover {
            background-color: #4338ca !important;
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
        <h2>Processing Your Analysis</h2>
        <div class="subtitle">Please wait while we prepare your results</div>

        <div class="spinner"></div>

        <!-- Progress bar -->
        <div class="progress-bar">
            <div class="progress-bar-inner" id="progressBar"></div>
        </div>

        <!-- Steps -->
        <div class="steps">
            <div class="step" id="step1"><div class="dot"></div>Fetching NCBI data</div>
            <div class="step" id="step2"><div class="dot"></div>Parsing sequences</div>
            <div class="step" id="step3"><div class="dot"></div>Running analysis</div>
            <div class="step" id="step4"><div class="dot"></div>Generating results</div>
        </div>

        <!-- Cancel Button -->
        <button class="cancel-btn" onclick="cancelJob()">Cancel Job</button>

    </div>
</div>

<!-- Hidden form -->
<form id="autoForm" method="POST" action="run_analysis.php">
    <?php
    foreach ($_POST as $key => $value) {
        if (is_array($value)) {
            foreach ($value as $v) {
                echo "<input type='hidden' name='{$key}[]' value='" . htmlspecialchars($v) . "'>";
            }
        } else {
            echo "<input type='hidden' name='$key' value='" . htmlspecialchars($value) . "'>";
        }
    }
    ?>
</form>

<script>
let cancelled = false;

function cancelJob() {
    cancelled = true;

    fetch("cancel_job.php", {
        method: "POST"
    }).then(() => {
        alert("Job cancelled.");
        window.location.href = "analysis_UI.php";
    });
}

const steps = [
    document.getElementById("step1"),
    document.getElementById("step2"),
    document.getElementById("step3"),
    document.getElementById("step4")
];

const progressBar = document.getElementById("progressBar");

let currentStep = 0;

function activateStep(index) {
    if (index < steps.length) {
        steps[index].classList.add("active");

        if (index > 0) {
            steps[index - 1].classList.remove("active");
            steps[index - 1].classList.add("completed");
        }

        progressBar.style.width = ((index + 1) / steps.length * 100) + "%";
    }
}

function runSteps() {
    const interval = setInterval(() => {

        if (cancelled) {
            clearInterval(interval);
            return;
        }

        activateStep(currentStep);
        currentStep++;

        if (currentStep >= steps.length) {
            clearInterval(interval);

            steps[steps.length - 1].classList.add("completed");

            setTimeout(() => {
                if (!cancelled) {
                    document.getElementById("autoForm").submit();
                }
            }, 800);
        }

    }, 800);
}

window.onload = runSteps;
</script>

</body>
</html>
