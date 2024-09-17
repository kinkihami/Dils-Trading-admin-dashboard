<?php
$action = $_REQUEST['action'];

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

file_put_contents($logFile, "   ", FILE_APPEND | LOCK_EX);

if (!empty($action)) {
    require_once 'functions.php';
    $obj = new Functions();
}


if ($action == "addcategory" && !empty($_POST)) {
    try {
        $logFile = 'path_to_your_log_file.log'; // define your log file path
        $valid_extensions = array('jpg', 'jpeg', 'png'); // valid extensions
        $path = '../category_image/'; // upload directory

        $playerId = (!empty($_POST['id'])) ? $_POST['id'] : '';
        $name = $_POST['name'];
        $hassubcategory = isset($_POST['has']) ? $_POST['has'] : 0;
        $parentid=$_POST['parentid'];

        if ($playerId) {
            if (!empty($_FILES["image"]['name'])) {
                $category = (!empty($_POST['image'])) ? $_POST['image'] : '';

                if (!empty($category) && file_exists('../category_image/' . $category)) {
                    unlink('../category_image/' . $category);
                }

                $categoryimage = $_FILES['image']['name'];
                $tmp = $_FILES['image']['tmp_name'];
                $ext = strtolower(pathinfo($categoryimage, PATHINFO_EXTENSION));
                $final_image = rand(1000, 1000000) . $categoryimage;

                if (in_array($ext, $valid_extensions)) {
                    $path = $path . strtolower($final_image);

                    if (move_uploaded_file($tmp, $path)) {
                        $categoryData = [
                            'category' => $name,
                            'image' => $final_image,
                            'has_subcategory' => $hassubcategory,
                            'parent_category_id' => $parentid
                        ];

                        $obj->update($categoryData, $playerId);
                        $response = [
                            'success' => true,
                            'message' => "category updated successfully!",
                        ];
                    }
                }
            } else {
                $categoryData = [
                    'category' => $name,
                    'has_subcategory' => $hassubcategory,
                    'parent_category_id' => $parentid
                ];

                $obj->update($categoryData, $playerId);
                $response = [
                    'success' => true,
                    'message' => "category updated successfully!",
                    'parent_category_id' => $parentid
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
                        $categoryData = [
                            'category' => $name,
                            'image' => $final_image,
                            'has_subcategory' => $hassubcategory,
                            'parent_category_id' => $parentid
                        ];

                        $playerId = $obj->addcategory($categoryData);
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


if ($action == "getcategoryfields") {
    
    $id = (!empty($_GET['id'])) ? $_GET['id'] : '';
    if (!empty($id)) {
        $category = $obj->getRow('id', $id);
        echo json_encode($category);
        exit();
    }
}


if ($action == "getcategory") {
    // Append data to the log file
    //file_put_contents($logFile, $logData, FILE_APPEND | LOCK_EX);
    $page = (!empty($_GET['page'])) ? $_GET['page'] : 1;
    $id = (!empty($_GET['id'])) ? $_GET['id'] : '';
    $limit = 15;
    $start = ($page - 1) * $limit;

    $category = $obj->getRows($start, $limit, $id);
    if (!empty($category)) {
        $categorylist = $category;
    } else {
        $categorylist = [];
    }
    $total = $obj->getCount($id);
    $playerArr = ['count' => $total,'category' => $categorylist];
    echo json_encode($playerArr);
    exit();
}

?>