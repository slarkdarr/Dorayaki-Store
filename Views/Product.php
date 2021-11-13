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
    header("location: /Views/Login.php");
}

// Validate id exists
if (!isset($_GET['id'])) {
    header("location: /index.php");
} else {
    // Sqlite conn
    $databasePath = '../database/' . DATABASE_NAME . '.sqlite';
    $pdo = (new SQLiteConnection())->connect($databasePath);
    if ($pdo !== null) {
        $id = $_GET['id'];
        $Product = new Product($pdo);
        $item = $Product->whereId($id);
        if (count($item) < 1) {
            header("location: /index.php");
        } else {
            // get first item
            $item = $item[0];
        }
    } else {
        // Fail to connect
        header("location: /index.php");
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../Assets/css/detail.css">
    <link href="//db.onlinewebfonts.com/c/4a47df713b67c858644e569c4977de19?family=Dorayaki" rel="stylesheet" type="text/css" />
    <!-- Font awesome -->
    <script src="https://kit.fontawesome.com/55c10e2ab9.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../Assets/css/deleteConfirmation.css" />

    <title>World Break Down</title>
</head>

<body>
    <!-- Navbar -->
    <?php include '../partials/navbar.php'; ?>
    <!-- End navbar -->

    <!-- Content -->
    <div class="content">
        <div class="grid-container">
            <div class="grid-item image-grid">
                <img id="image" src="<?php echo '../' . $item['image'] ?>" alt="" style="border-radius:20px;">
            </div>
            <div class="grid-item">
                <div class="header">
                    <a href="../index.php" id="back">←</a>
                    <h1 class="headerTitle" id="title"><?php echo $item['name'] ?></h1>
                </div>
                <div class="description" id="description">
                    <p><?php echo $item['description'] ?></p>
                    <p>Available : <?php echo $item['stock'] ?></p>
                    <p>Sold : <?php echo $item['sold'] ?></p>
                </div>
                <div class="footer">
                    <p id="harga">Rp<?php $price = $item['price']; echo number_format($price,2,",","."); ?></p>
                </div>
                <div class="button-group">
                    <?php if ($role == 'admin') { ?>
                        <a href="ChangeStock.php?id=<?php echo $id ?>"><input type="button" name="someAction" value="Change Stock" id="button" /></a>
                        <a href="EditProduct.php?id=<?php echo $id ?>"><input type="button" name="someAction" value="Edit Product" id="button" /></a>
                        <input onclick="document.getElementById('id01').style.display='block'" type="button" name="someAction" value="Delete Product" id="button" />
                    <?php } ?>
                    <?php if ($role == 'user') { ?>
                        <a href="BuyProduct.php?id=<?php echo $id ?>"><input type="button" name="someAction" value="Buy" id="button" /></a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Add role  -->
    <div id="id01" class="modal">
        <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">×</span>
        <form class="modal-content" action="/action_page.php">
            <div class="container">
                <h1>Delete Dorayaki</h1>
                <p>Are you sure you want to delete this dorayaki?</p>

                <div class="clearfix">
                    <button type="button" onclick="document.getElementById('id01').style.display='none'" class="cancelbtn">Cancel</button>
                    <button type="button" onclick="del(<?php echo $id ?>)">Delete</button>
                </div>
            </div>
        </form>
    </div>
    <!-- end content -->


    <!-- Footer -->
    <?php include '../partials/footer.php'; ?>
    <!-- End Footer -->

    <script>
        // Get the modal
        var modal = document.getElementById('id01');

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
        function del(id){
            document.getElementById('id01').style.display='none';
            location.href='../Middlewares/delete.php?id='+id;
        }
    </script>
</body>

</html>