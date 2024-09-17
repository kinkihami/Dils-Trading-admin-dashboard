<?php
$action = $_REQUEST['action'];

if (!empty($action)) {
    require_once 'functions.php';
    $obj = new Functions();
}

$logFile = 'logfile.txt';

// Data to log
$logData = date('Y-m-d H:i:s') . " - User accessed the website.\n";

// Check if the log file exists, if not, create it
if (!file_exists($logFile)) {
    // Create the log file
    $fileHandle = fopen($logFile, 'w') or die("Unable to create log file!");
    fclose($fileHandle);
}




if ($action == "getorder") {
    // Append data to the log file
    //file_put_contents($logFile, $logData, FILE_APPEND | LOCK_EX);

    $status = (!empty($_GET['status'])) ? $_GET['status'] : 4;
    $page = (!empty($_GET['page'])) ? $_GET['page'] : 1;
    $limit = 15;
    $start = ($page - 1) * $limit;
    
    file_put_contents($logFile, $status, FILE_APPEND | LOCK_EX);

    $order = $obj->getRows($start, $limit, $status);
    
    
    if (!empty($order)) {
        $orderlist = $order;
    } else {
        $orderlist = [];
    }
    $total = $obj->getCount($status);
    $playerArr = ['count' => $total,'order' => $orderlist];
    echo json_encode($playerArr);
    exit();
}

if($action=='sendnotification'){
    $url='https://fcm.googleapis.com/fcm/send';
}

?>