<?php session_start();


$logFile = 'logfile.txt';

// Data to log
$logData = date('Y-m-d H:i:s') . " - User accessed the website.\n";

// Check if the log file exists, if not, create it
if (!file_exists($logFile)) {
    // Create the log file
    $fileHandle = fopen($logFile, 'w') or die("Unable to create log file!");
    fclose($fileHandle);
}


file_put_contents($logFile, 'haaiiii  ', FILE_APPEND | LOCK_EX);


file_put_contents($logFile, isset($_SESSION['username']), FILE_APPEND | LOCK_EX);


if (!isset($_SESSION['username'])) {

    ?>
    <script> window.location.href = "login.php";</script>
    <?php
} else {

    ?>
    <script> window.location.href = "dashboard/index.php";</script>
    <?php
}

?>