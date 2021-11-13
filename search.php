<?php
session_start();
ob_start();
include_once('config.php');
include_once('database/SQLiteConnection.php');
include_once('Model/Product.php');

// Validate logged in
if (isset($_COOKIE['token']) && isset($_COOKIE['userLoggedIn'])) {
    if ((md5($_COOKIE['userLoggedIn'] . SECRET_WORD)) === $_COOKIE['token']) {
        $role = $_SESSION['role'];
    }
} else {
    // Destroy session
    session_unset();
    session_destroy();
    header("location: /Views/Login.php");
}

// Check search for pagination ?keyword=
if (!isset($_GET['keyword']) || (strlen($_GET['keyword']) < 2)) {
    header("location: /index.php");
} else {
    $keyword = $_GET['keyword'];
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="Assets/css/index.css" />
    <link rel="stylesheet" href="Assets/css/style.css" />
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
            <div class="products" id="products" data-id="<?php echo $keyword ?>">
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
        var products = document.getElementById('products');
        let keyword = products.getAttribute('data-id');
        load_data(keyword, 1);

        function load_data(query = '', page_number = 1) {
            var form_data = new FormData();
            form_data.append('query', query)
            form_data.append('page', page_number);

            var ajax = new XMLHttpRequest();
            ajax.open('POST', 'Middlewares/search.php');
            ajax.send(form_data);

            ajax.onreadystatechange = function() {
                if (ajax.readyState == 4 && ajax.status == 200) {
                    var response = JSON.parse(ajax.responseText);
                    var pagination = document.getElementById('pagination_link');
                    products.innerHTML = response.products;
                    pagination.innerHTML = response.pagination;
                }
            }
        }
    </script>
</body>

</html>