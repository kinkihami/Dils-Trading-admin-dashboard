<?php
$action = $_REQUEST['action'];

if (!empty($action)) {
    require_once 'functions.php';
    $obj = new Functions();
}

if ($action == "adddealer" && !empty($_POST)) {

   
    
    // Append data to the log file

    $id = $_POST['id'];
    $owner = $_POST['owner'];
    $shop = $_POST['shop'];
    $address = $_POST['address'];
    $gst = $_POST['gst'];
    $phone = $_POST['phone'];
    $premium = $_POST['premium'];

    
    $dealerData = [
            'owner' => $owner,
            'gstno' => $gst,
            'phonenumber' => $phone,
            'shopname' => $shop,
            'address' => $address,
            'premium' => $premium,
        ];

    if ($id==null) {
        
        $id = $obj->adddealer($dealerData);
        if ($id) {
            $response = [
                'success' => true,
                'message' => "dealer added successfully!",
            ];
        } else {
            $response = [
                'success' => false,
                'message' => "Error adding dealer!"
            ];
        }
        

    }
    
    else {
        $obj->update($dealerData, $id);
        $response = [
            'success' => true,
            'message' => "dealer updated successfully!",
        ];
    }

    echo json_encode($response);
    exit();

}

if ($action == "deletedealer") {
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


if ($action == "getdealerfields") {
    
    $id = (!empty($_GET['id'])) ? $_GET['id'] : '';
    if (!empty($id)) {
        $dealer = $obj->getRow('id', $id);
        echo json_encode($dealer);
        exit();
    }
}


if ($action == "getdealer") {
    // Append data to the log file
    //file_put_contents($logFile, $logData, FILE_APPEND | LOCK_EX);
    $page = (!empty($_GET['page'])) ? $_GET['page'] : 1;
    $limit = 15;
    $start = ($page - 1) * $limit;

    $dealer = $obj->getRows($start, $limit);
    if (!empty($dealer)) {
        $dealerlist = $dealer;
    } else {
        $dealerlist = [];
    }
    $total = $obj->getCount();
    $playerArr = ['count' => $total,'dealer' => $dealerlist];
    echo json_encode($playerArr);
    exit();
}

?>