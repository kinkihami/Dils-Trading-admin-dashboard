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


if ($action == "addimage" && !empty($_POST)) {
    file_put_contents($logFile, "hai, iam inside addimage", FILE_APPEND | LOCK_EX);
    try {
        $logFile = 'path_to_your_log_file.log'; // define your log file path
        file_put_contents($logFile, "Haiiii", FILE_APPEND | LOCK_EX);
        $valid_extensions = array('jpg', 'jpeg', 'png'); // valid extensions
        $path = '../product_image/'; // upload directory


        $playerId = $_POST['id'];
        $productid = $_POST['productid'];

        if ($playerId) {
            if (!empty($_FILES["image"]['name'])) {
                $product = (!empty($_POST['image'])) ? $_POST['image'] : '';

                if (!empty($product) && file_exists('../product_image/' . $product)) {
                    unlink('../product_image/' . $product);
                }

                $productimage = $_FILES['image']['name'];
                $tmp = $_FILES['image']['tmp_name'];
                $ext = strtolower(pathinfo($productimage, PATHINFO_EXTENSION));
                $final_image = rand(1000, 1000000) . $productimage;

                if (in_array($ext, $valid_extensions)) {
                    $path = $path . strtolower($final_image);

                    if (move_uploaded_file($tmp, $path)) {
                        $imageData = [
                            'image' => $final_image,
                        ];

                        $obj->update($imageData, $playerId);
                        $response = [
                            'success' => true,
                            'message' => "product updated successfully!",
                        ];
                    }
                }
            }
        } else {
            if (!empty($_FILES["image"]['name'])) {
                $note = $_FILES['image']['name'];
                $tmp = $_FILES['image']['tmp_name'];
                $ext = strtolower(pathinfo($note, PATHINFO_EXTENSION));
                $final_image = rand(1000, 1000000) . $note;

                file_put_contents($logFile, $final_image, FILE_APPEND | LOCK_EX);

                if (in_array($ext, $valid_extensions)) {
                    $path = $path . strtolower($final_image);
                    if (move_uploaded_file($tmp, $path)) {
                        $imageData = [
                            'productid' => $productid,
                            'image' => $final_image,
                        ];

                        $playerId = $obj->addimage($imageData);
                        $data = array('msg' => 'success');
                        echo json_encode($data);
                        exit(); // Make sure to exit after sending the response
                    }
                }
            } else {
                $data = array('msg' => 'Please select file');
                echo json_encode($data);
                exit(); // Make sure to exit after sending the response
            }
        }

        if (!empty($playerId)) {
            $player = $obj->getRow('id', $playerId);
            if ($player) {
                echo json_encode($player);
            } else {
                echo json_encode(['error' => 'Player not found']);
            }
        } else {
            echo json_encode(['error' => 'Player ID is empty']);
        }
        exit();
    } catch (Exception $e) {
        file_put_contents($logFile, $e->getMessage(), FILE_APPEND | LOCK_EX);
        echo json_encode(['error' => 'An error occurred']);
        exit();
    }
}

if ($action == "deleteimage") {
    $playerId = (!empty($_POST['id'])) ? $_POST['id'] : '';
    if (!empty($playerId)) {
        $isDeleted = $obj->deleteRow($playerId);
        if ($isDeleted) {
            $message = ['deleted' => 1];
        } else {
            $message = ['deleted' => 0];
        }
       echo json_encode($message);
    exit();
    }
}


if ($action == "getimagefields") {
    
    $id = (!empty($_GET['id'])) ? $_GET['id'] : '';
    if (!empty($id)) {
        $image = $obj->getRow('id', $id);
        echo json_encode($image);
        exit();
    }
}


if ($action == "getimage") {
    // Append data to the log file
    //file_put_contents($logFile, $logData, FILE_APPEND | LOCK_EX);
    $page = (!empty($_GET['page'])) ? $_GET['page'] : 1;
    $id=$_GET['id'];
    $limit = 15;
    $start = ($page - 1) * $limit;

    $image = $obj->getRows($start, $limit,$id);
    if (!empty($image)) {
        $imagelist = $image;
    } else {
        $imagelist = [];
    }
    $total = $obj->getCount($id);
    $playerArr = ['count' => $total,'image' => $imagelist];
    echo json_encode($playerArr);
    exit();
}

?>