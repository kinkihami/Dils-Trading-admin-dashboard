<?php session_start(); 

 if (!isset($_SESSION['username'])) {
                     
                 ?>
  <script> window.location.href = "../login.php";</script>
  <?php
                  }
          
                  
                  ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Document</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <!----custom CSS---->
    <link rel="stylesheet" href="../css/custom.css">

    <!--google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!--google material icon-->
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet">

</head>

<body>

    <div class="wrapper">

        <div class="body-overlay"></div>

        <?php include_once '../includes/sidebar.php'; ?>

        <div id="content">

            <?php include_once '../includes/topnavbar.php'; ?>

            <div class="main-content">

                <?php include_once 'order_table.php'; ?>

                <nav id="pagination">
                </nav>
                <input type="hidden" name="currentpage" id="currentpage" value="1">


            </div>
        </div>

    </div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script src="script.js"></script>
   <script type="text/javascript">
        $(document).ready(function () {
            $(".xp-menubar").on('click', function () {
                $("#sidebar").toggleClass('active');
                $("#content").toggleClass('active');
            });

            $('.xp-menubar,.body-overlay').on('click', function () {
                $("#sidebar,.body-overlay").toggleClass('show-nav');
            });

        });
    </script>


</body>

</html>