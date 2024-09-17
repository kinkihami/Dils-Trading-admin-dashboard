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


if ($action == "addproduct" && !empty($_POST)) {
    try {
        $logFile = 'path_to_your_log_file.log'; // define your log file path
        file_put_contents($logFile, "Haiiii", FILE_APPEND | LOCK_EX);
        $valid_extensions = array('jpg', 'jpeg', 'png'); // valid extensions
        $path = '../product_image/'; // upload directory

        $playerId = (!empty($_POST['id'])) ? $_POST['id'] : '';
        $name = $_POST['name'];
        $description = $_POST['desc'];
        $catid = $_POST['catid'];
        $normalprice = $_POST['price'];
        $unit = $_POST['unitselect'];
        $fast_moving = isset($_POST['fast']) ? $_POST['fast'] : 0;

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
                        $productData = [
                            'productname' => $name,
                            'categoryid' => $catid,
                            'description' => $description,
                            'price' => $normalprice,
                            'image' => $final_image,
                            'unitid' => $unit,
                            'fast_moving' => $fast_moving,
                        ];

                        $obj->update($productData, $playerId);
                        $response = [
                            'success' => true,
                            'message' => "product updated successfully!",
                        ];
                    }
                }
            } else {
                $productData = [
                    'productname' => $name,
                            'categoryid' => $catid,
                            'description' => $description,
                            'price' => $normalprice,
                            'unitid' => $unit,
                            'fast_moving' => $fast_moving,
                ];

                $obj->update($productData, $playerId);
                $response = [
                    'success' => true,
                    'message' => "product updated successfully!"
                ];
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
                        $productData = [
                            'productname' => $name,
                            'categoryid' => $catid,
                            'description' => $description,
                            'price' => $normalprice,
                            'image' => $final_image,
                            'unitid' => $unit,
                            'fast_moving' => $fast_moving,
                        ];

                        $playerId = $obj->addproduct($productData);
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






if ($action == "disableproduct") {
    $id = $_POST['id'];
    if (!empty($id)) {
        $obj->disable($id);
        $response = [
            'success' => true,
            'message' => "Data updated successfully!",
        ];
        echo json_encode($response);
        exit();
    }
}

if ($action == "getproductfields") {
    
    $id = (!empty($_GET['id'])) ? $_GET['id'] : '';
    if (!empty($id)) {
        $product = $obj->getRow('id', $id);
        echo json_encode($product);
        exit();
    }
}

if ($action == "getunits") {

    $players = $obj->getunits();
    if (!empty($players)) {
        $playerslist = $players;
    } else {
        $playerslist = [];
    }

 
    $total = $obj->getunitCount();
    $playerArr = ['count' => $total, 'units' => $playerslist];
    echo json_encode($playerArr);
    exit();
}


if ($action == "getproduct") {
    // Append data to the log file
    
    $page = (!empty($_GET['page'])) ? $_GET['page'] : 1;
    $id = (!empty($_GET['id'])) ? $_GET['id'] : '';
    $limit = 15;
    $start = ($page - 1) * $limit;
    file_put_contents($logFile, $id, FILE_APPEND | LOCK_EX);

    $product = $obj->getRows($start, $limit, $id);
    if (!empty($product)) {
        $productlist = $product;
    } else {
        $productlist = [];
    }
    $total = $obj->getCount($id);
    file_put_contents($logFile, $total, FILE_APPEND | LOCK_EX);
    $playerArr = ['count' => $total,'product' => $productlist];
    echo json_encode($playerArr);
    exit();
}

?>