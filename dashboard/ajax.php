<?php
$action = $_REQUEST['action'];

if (!empty($action)) {
    require_once 'functions.php';
    $obj = new Functions();
}


if ($action == "getcount") {

    $order = $obj->orderCount();
    $pendingorder = $obj->pendingorderCount();
    $delieverdorder = $obj->delieveredorderCount();
    $dealers = $obj->dealersCount();
    $playerArr = ['order' => $order,'pending' => $pendingorder,'delivered' => $delieverdorder,'dealer' => $dealers];
    echo json_encode($playerArr);
    exit();
}


?>