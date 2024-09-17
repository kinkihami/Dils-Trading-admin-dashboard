<?php
$action = $_REQUEST['action'];

if (!empty($action)) {
    require_once 'functions.php';
    $obj = new Functions();
}

if ($action == "addunit" && !empty($_POST)) {

   
    
    // Append data to the log file

    $id = $_POST['id'];
    $unit = $_POST['unit'];

    
    $unitData = [
            'unit' => $unit,
        ];

    if ($id==null) {
        
        $id = $obj->addunit($unitData);
        if ($id) {
            $response = [
                'success' => true,
                'message' => "unit added successfully!",
            ];
        } else {
            $response = [
                'success' => false,
                'message' => "Error adding unit!"
            ];
        }
        

    }
    
    else {
        $obj->update($unitData, $id);
        $response = [
            'success' => true,
            'message' => "unit updated successfully!",
        ];
    }

    echo json_encode($response);
    exit();

}

if ($action == "deleteunit") {
    $id = (!empty($_POST['id'])) ? $_POST['id'] : '';
    if (!empty($id)) {
        $isDeleted = $obj->deleteRow($id);
        if ($isDeleted) {
            $message = ['deleted' => 1];
        } else {
            $message = ['deleted' => 0];
        }
        echo json_encode($message);
        exit();
    }
}


if ($action == "getunitfields") {
    
    $id = (!empty($_GET['id'])) ? $_GET['id'] : '';
    if (!empty($id)) {
        $unit = $obj->getRow('id', $id);
        echo json_encode($unit);
        exit();
    }
}


if ($action == "getunit") {
    // Append data to the log file
    //file_put_contents($logFile, $logData, FILE_APPEND | LOCK_EX);
    $page = (!empty($_GET['page'])) ? $_GET['page'] : 1;
    $limit = 15;
    $start = ($page - 1) * $limit;

    $unit = $obj->getRows($start, $limit);
    if (!empty($unit)) {
        $unitlist = $unit;
    } else {
        $unitlist = [];
    }
    $total = $obj->getCount();
    $playerArr = ['count' => $total,'unit' => $unitlist];
    echo json_encode($playerArr);
    exit();
}

?>