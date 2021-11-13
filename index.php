<?php
// Validate logged in
session_start();
ob_start();
include_once('config.php');
include_once('database/SQLiteConnection.php');
include_once('Model/Product.php');

function alert($msg)
{
    echo "<script type='text/javascript'>alert('$msg');</script>";
    setcookie('message', '', time() - 3600, '/');
}
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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="Assets/css/index.css" />
    <link href="//db.onlinewebfonts.com/c/4a47df713b67c858644e569c4977de19?family=Dorayaki" rel="stylesheet" type="text/css" />
    <!-- Font awesome -->
    <script src="https://kit.fontawesome.com/55c10e2ab9.js" crossorigin="anonymous"></script>

    <title>World Break Down</title>
</head>

<body>
    <!-- Navbar -->
    <?php include 'partials/navbar.php'; ?>
    <!-- End navbar -->

    <!-- Content -->
    <div class="content">
        <?php if ($role === 'admin') { ?>
            <div class="add-product">
                <a href="Views/CreateProduct.php" class="button"><i class="fas fa-plus"></i>Variant</a>
            </div>
        <?php } ?>
        <section class="product-section">
            <div class="products" id="products">
                <!-- Data by dashboard.php here -->
            </div>
        </section>


        <div class="pagination" id="pagination_link">
            <!-- Pagination link -->
        </div>

    </div>
    <!-- end content -->


    <!-- Footer -->
    <?php include 'partials/footer.php'; ?>
    <!-- End Footer -->

    <script type="text/javascript">
        load_data(1);

        function load_data(page_number = 1) {
            var form_data = new FormData();

            form_data.append('page', page_number);

            var ajax = new XMLHttpRequest();
            ajax.open('POST', 'Middlewares/dashboard.php');
            ajax.send(form_data);

            ajax.onreadystatechange = function () {
                if (ajax.readyState == 4 && ajax.status == 200){
                    var response = JSON.parse(ajax.responseText);
                    var products = document.getElementById('products');
                    var pagination = document.getElementById('pagination_link');
                    products.innerHTML = response.products;
                    pagination.innerHTML = response.pagination;
                }
            }
        }
    </script>

</body>

</html>