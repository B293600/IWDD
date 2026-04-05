<?php
session_start();
require_once 'login.php';

echo <<<HTML
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
HTML;

// ---------------------------
// TEST DATABASE CONNECTION (already created in login.php)
// ---------------------------
try {
    // Just test the connection works
    $stmt = $pdo->query("SELECT * FROM Manufacturers");
    $rows = $stmt->rowCount();

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

// ---------------------------
// CREATE SESSION MASK (unchanged logic)
// ---------------------------
$mask = 0;
for ($j = 0; $j < $rows; ++$j) {
    $mask = (2 * $mask) + 1;
}

$_SESSION['supmask'] = $mask;

// ---------------------------
// LOGIN FORM
// ---------------------------
echo <<<HTML
<script>
function validate(form) {
    let fail = "";

    if (form.fn.value == "") fail = "Must Give Forename ";
    if (form.sn.value == "") fail += "Must Give Surname";

    if (fail == "") return true;
    else {
        alert(fail);
        return false;
    }
}
</script>

<form action="index.php" method="post" onsubmit="return validate(this)">
<pre>
First Name  <input type="text" name="fn"/>
Second Name <input type="text" name="sn"/>
            <input type="submit" value="go" />
</pre>
</form>
HTML;

echo <<<HTML
</body>
</html>
HTML;

?>
