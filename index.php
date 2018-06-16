<?php
    session_start();



?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <!-- jQuery Validation Library -->
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>

    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>

    <!-- BootStrap -->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>


    <script src="js/script.js"></script>
    <script src="plugins/flipclock/flipclock.js"></script>
    <link rel="stylesheet" href="css/style.css"/>
    <link rel="stylesheet" href="plugins/flipclock/flipclock.css"/>


</head>
<body>

<header>
    <section class="container-fluid">
        <section class="row justify-content-center">
            <section class="col-lg-4">
                <h1 class="display-4">Time Manangement</h1>
            </section>
        </section>
    </section>
    <?php
        if(isset($_SESSION['user_id'])) {
            include("views/menu.php");
        }
    ?>
</header>

<section class="site-wrapper">
    <?php
        if(isset($_SESSION['user_id'])){
            include("views/timer.php");
            if(isset($_GET['page'])){
                $page = $_GET['page'];
                echo "<section class='content'>";

                switch ($page) {
                    case 'userstats':
                        include("views/userstats.php");
                        break;
                    case 'logs':
                        include("views/logs.php");
                        break;
                }

                echo "</section>";
            }else{
                include("views/userstats.php");
            }
        }else{
            include("views/login.php");
        }

    ?>
</section>

<footer>

</footer>

</body>
</html>
