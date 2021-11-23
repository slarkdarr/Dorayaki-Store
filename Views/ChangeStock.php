<?php
session_start();
ob_start();
include_once('../config.php');
include_once('../database/SQLiteConnection.php');

if (isset($_COOKIE['token']) && isset($_COOKIE['userLoggedIn'])) {
    if ((md5($_COOKIE['userLoggedIn'] . SECRET_WORD)) !== $_COOKIE['token'] || $_SESSION['role'] !== 'admin') {
        setcookie('message', 'Prohibited', time() + 3600, '/');
        header("location: /Views/Login.php");
    }
    $role = $_SESSION['role'];
} else {
    // Destroy session
    session_unset();
    session_destroy();
    setcookie('message', 'Prohibited', time() + 3600, '/');
    header("location: /Views/Login.php");
}

// Validate id exists
if (!isset($_GET['id'])) {
    header("location: /index.php");
} else {
    $id = $_GET['id'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../Assets/css/changeStock.css" />
    <!-- Font awesome -->
    <script src="https://kit.fontawesome.com/55c10e2ab9.js" crossorigin="anonymous"></script>

    <title>World Break Down</title>
</head>

<body>
    <!-- Navbar -->
    <?php include '../partials/navbar.php'; ?>
    <!-- End Navbar -->

    <!-- Content -->
    <div class="content">
        <div class="wrapper">
            <h3 class="title">CHANGE STOCK DORAYAKI</h3>
            <p class="text-only">Current Stock</p>
            <div id="stock" data-id="<?php echo $id ?>" class="text-only"></div>
            <form class="form" action="../Middlewares/changestock.php" method="POST" enctype="multipart/form-data">
                <div class="input-field">
                    <label for="price">Amount</label>
                    <input type="number" min='1' id="available-stock" name="stock" required>
                </div>

                <input type="hidden" id="id" name="id" value="<?php echo $id ?>">

                <div class="input-field">
                    <input class="button" type="submit" id="submit" name="change" value="Change">
                </div>

            </form>
        </div>
    </div>
    <!-- end content -->


    <!-- Footer -->
    <?php include '../partials/footer.php'; ?>
    <!-- End  Footer -->

    <!-- Script -->
    <script type="text/javascript">
        function loadStock() {
            let stock = document.getElementById("stock");
            let id = stock.getAttribute('data-id');
            let avail = document.getElementById('available-stock');
            let xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    const data = JSON.parse(xhttp.responseText);
                    stock.innerHTML = data.stock;
                    // console.log(data.stock);
                }
            };
            xhttp.open("GET", "../Middlewares/stock.php?id=" + id, true);
            xhttp.send();
        }
        setInterval(loadStock, 1000);
    </script>
    <!-- End Script -->
</body>

</html>