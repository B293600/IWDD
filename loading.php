<!DOCTYPE html>
<html>
<head>
    <title>Processing...</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 100px;
        }

        .spinner {
            border: 6px solid #f3f3f3;
            border-top: 6px solid #6A1FD1;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>

<h2>Processing your request...</h2>
<div class="spinner"></div>
<p>This may take a few moments (NCBI fetch + analysis running).</p>

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
document.getElementById("autoForm").submit();
</script>

</body>
</html>
