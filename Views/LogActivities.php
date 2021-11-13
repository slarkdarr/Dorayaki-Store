<?php
session_start();
ob_start();
include_once('../config.php');
include_once('../database/SQLiteConnection.php');
include_once('../Model/History.php');

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
    header("location: /Views/Login.php");
}
    // Sqlite conn
    $databasePath = '../database/' . DATABASE_NAME . '.sqlite';
    $pdo = (new SQLiteConnection())->connect($databasePath);
    $username = $_SESSION['username'];
    if ($pdo !== null) {
        $History = new History($pdo);
        if ($role=='admin') {
            $item = $History->get();
        }
        else {
            $item = $History->whereUserName($username);
        }
    //     $id = $_GET['id'];
    //     $Product = new Product($pdo);
    //     $item = $Product->whereId($id);
    //     if (count($item) < 1) {
    //         header("location: /index.php");
    //     } else {
    //         // get first item
    //         $item = $item[0];
    //     }
    } else {
    //     // Fail to connect
    //     header("location: /index.php");
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../Assets/css/logactivities.css">
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
        <div class="logact">
            <h1>Log Activities</h1>
            <table>
                <tr>
                    <th>Date and Time</th>
                    <th>Dorayaki Name</th>
                    <th>Amount</th>
                    <th>Total Price</th>
                    <th>Username</th>
                </tr>
                <?php foreach ($item as $key => $value) { ?>
                    <tr>
                        <td><?php echo $value['date'] ?></td>
                        <td><?php echo "<a href='Product.php?id={$value['product_id']}' style='text-decoration:none;color:black;'>".$value['product_name'] ?></a></td>
                        <td><?php echo $value['quantity'] ?></td>
                        <td><?php $price = number_format($value['total_price'],2,",","."); echo "Rp$price,00" ?></td>
                        <td><?php echo $value['username'] ?></td>
                    </tr>
                <?php } ?>
                <?php if (count($item)<1) { ?>
                    <tr>
                        <td colspan="5" align="center">No Available Data</td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
    <!-- end content -->


    <!-- Footer -->
    <?php include '../partials/footer.php'; ?>
    <!-- End Footer -->
</body>

</html>