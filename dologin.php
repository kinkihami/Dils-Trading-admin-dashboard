<?php

session_start();
include_once 'database.php';
$databaseService = new Database();
$conn = $databaseService->getConnection();

if (isset($_POST['username'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];



    $query = "SELECT * FROM login WHERE username = ? and password=?";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(1, $username);
    $stmt->bindParam(2, $password);
    $stmt->execute();
    $num = $stmt->rowCount();

    if ($num > 0) {

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$result) { // again, no rowCount() is needed!
            // echo 'No data found';
        } else {
            // $json['status']="success";
            //array_push($response,$json);

            foreach ($result as $row) {
                $level = $row['level'];
                $_SESSION["username"] = $username;
                $_SESSION["password"] = $password;
                
               
                
                if ($level == 1) {
                   header("Location: index.php");
                   exit;
                } else{
                    header("Location: login.php");
                   exit;
                }

            }

        }
    } else {
        echo '<script type ="text/JavaScript">';
        echo 'alert("Invalid Username or Password")';
        echo '</script>';
        header("Location: $baseUrl./login.php");
        // header("Location: login.php");
        //  }
    }

}


?>