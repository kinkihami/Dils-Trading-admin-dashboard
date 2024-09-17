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


file_put_contents($logFile, $action, FILE_APPEND | LOCK_EX);

if ($action == "addvariant" && !empty($_POST)) {

   
    
    // Append data to the log file

    $id = $_POST['id'];
    $size = $_POST['size'];
    $price = $_POST['price'];
    $productid = $_POST['productid'];

    
    if ($id==null) {
        $variantData = [
            'size' => $size,
            'price' => $price,
            'productid' => $productid,
        ];
        $id = $obj->addvariant($variantData);
        if ($id) {
            $response = [
                'success' => true,
                'message' => "variant added successfully!",
            ];
        } else {
            $response = [
                'success' => false,
                'message' => "Error adding variant!"
            ];
        }
    }
    
    else {
        $variantData = [
            'size' => $size,
            'price' => $price,
        ];
        $obj->update($variantData, $id);
        $response = [
            'success' => true,
            'message' => "variant updated successfully!",
        ];
    }

    echo json_encode($response);
    exit();

}


if ($action == "deletevariant") {
    $id = $_POST['id'];
    if (!empty($id)) {
        $obj->deleteRow($id);
        $response = [
            'success' => true,
            'message' => "Data updated successfully!",
        ];
        echo json_encode($response);
        exit();
    }
}

if ($action == "getvariantfields") {
    
    $id = (!empty($_GET['id'])) ? $_GET['id'] : '';
    if (!empty($id)) {
        $variant = $obj->getRow('id', $id);
        file_put_contents($logFile, $variant, FILE_APPEND | LOCK_EX);
        echo json_encode($variant);
        exit();
    }
}


if ($action == "getvariant") {
    // Append data to the log file
    //file_put_contents($logFile, $logData, FILE_APPEND | LOCK_EX);
    $page = (!empty($_GET['page'])) ? $_GET['page'] : 1;
    $id = (!empty($_GET['id'])) ? $_GET['id'] : '';
    $limit = 15;
    $start = ($page - 1) * $limit;

    $variant = $obj->getRows($start, $limit, $id);
    if (!empty($variant)) {
        $variantlist = $variant;
    } else {
        $variantlist = [];
    }
    $total = $obj->getCount($id);
    $playerArr = ['count' => $total,'variant' => $variantlist];
    echo json_encode($playerArr);
    exit();
}

?>