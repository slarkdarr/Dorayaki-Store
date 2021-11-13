<?php
session_start();
ob_start();
include_once('../config.php');
include_once('../database/SQLiteConnection.php');
include_once('../Model/Product.php');

function alert($msg)
{
    echo "<script type='text/javascript'>alert('$msg');</script>";
    setcookie('message', '', time() - 3600, '/');
}
// Validate logged in
if (isset($_COOKIE['token']) && isset($_COOKIE['userLoggedIn'])) {
    if ((md5($_COOKIE['userLoggedIn'] . SECRET_WORD)) === $_COOKIE['token']) {
        if (isset($_COOKIE['message'])) {
            alert($_COOKIE['message']);
        }
        $role = $_SESSION['role'];
    }
} else {
    // Destroy session
    session_unset();
    session_destroy();
    // header("location: /Views/Login.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../Assets/css/base.css">
    <link rel="stylesheet" href="../Assets/css/notfound.css">
    <link href="//db.onlinewebfonts.com/c/4a47df713b67c858644e569c4977de19?family=Dorayaki" rel="stylesheet" type="text/css" />
    <!-- Font awesome -->
    <script src="https://kit.fontawesome.com/55c10e2ab9.js" crossorigin="anonymous"></script>

    <title>World Break Down</title>
</head>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../Assets/css/base.css">
    <link href="//db.onlinewebfonts.com/c/4a47df713b67c858644e569c4977de19?family=Dorayaki" rel="stylesheet" type="text/css" />
    <!-- Font awesome -->
    <script src="https://kit.fontawesome.com/55c10e2ab9.js" crossorigin="anonymous"></script>

    <title>World Break Down</title>
</head>

<body>
    <!-- Navbar -->
    <?php include '../partials/navbar.php'; ?>
    <!-- End navbar -->

    <!-- Content -->
    <div class="content">
        <div class="not-found">
            <div>
                <h1>404</h1>
                <h2>Page Not Found</h2>
    
                <p>Page you are looking is not found</p>
                <a href="../index.php">
                    <button>Back Home</button>
                </a>
            </div>
        </div>
    </div>
    <!-- end content -->


    <!-- Footer -->
    <?php include '../partials/footer.php'; ?>
    <!-- End Footer -->
</body>

</html>