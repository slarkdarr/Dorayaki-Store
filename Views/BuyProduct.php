<?php
session_start();
ob_start();
include_once('../config.php');
include_once('../database/SQLiteConnection.php');

if (isset($_COOKIE['token']) && isset($_COOKIE['userLoggedIn'])) {
    if ((md5($_COOKIE['userLoggedIn'] . SECRET_WORD)) !== $_COOKIE['token'] || $_SESSION['role'] !== 'user') {
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
    <link rel="stylesheet" href="../Assets/css/detail.css">
    <link rel="stylesheet" href="../Assets/css/buyProduct.css" />
    <link rel="stylesheet" href="../Assets/css/buyconfirmation.css" />
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
            <h3 class="title">BUY DORAYAKI</h3>
            <p class="text-only">Current Stock</p>
            <div id="stock" data-id="<?php echo $id ?>" class="text-only"></div>
            <form class="form" action="../Middlewares/buyproduct.php" method="POST" enctype="multipart/form-data">
                <div class="input-field">
                    <label for="price">Amount</label>
                    <input onchange="changePrice()" type="number" min=1 max='' id="available-stock" name="stock" value="<?php echo $item['stock'] ?>" required>
                </div>
                <div id="price" class="text-only"></div>

                <input type="hidden" id="id" name="id" value="<?php echo $id ?>">

                <div class="input-field">
                    <input onclick="document.getElementById('id01').style.display='block'" type="button" name="someAction" value="Buy Product" id="button" />
                </div>

            </form>
        </div>
    </div>
    <!-- end content -->

    <!-- Confirmation Button -->
    <div id="id01" class="modal">
        <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">×</span>
        <form class="modal-content" action="/action_page.php">
            <div class="container">
                <h1>Buy Dorayaki</h1>
                <p>Are you sure you want to buy this dorayaki?</p>

                <div class="clearfix">
                    <button type="button" onclick="document.getElementById('id01').style.display='none'" class="cancelbtn">Cancel</button>
                    <button type="button" onclick="confirm()">Buy</button>
                </div>
            </div>
        </form>
    </div>
    <!-- End Confirmation Button -->

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
                    avail.max = data.stock;
                    // console.log(data);
                }
            };
            xhttp.open("GET", "../Middlewares/stock.php?id=" + id, true);
            xhttp.send();
        }
        setInterval(loadStock, 1000);

        function changePrice() {
            let stock = document.getElementById("stock");
            let avail = document.getElementById('available-stock').value;
            let id = stock.getAttribute('data-id');
            let price = document.getElementById('price');
            let xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    const data = JSON.parse(xhttp.responseText);
                    price.innerHTML = `Total Price : Rp${(avail * data.price).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")},00`;
                    // console.log(data);
                }
            };
            xhttp.open("GET", "../Middlewares/stock.php?id=" + id, true);
            xhttp.send();
        }

        function confirm() {
            let stock = document.getElementById("stock");
            var id = stock.getAttribute('data-id');
            var amount = document.getElementById('available-stock').value;
            location.href = `../Middlewares/buyproduct.php?id=${id}&amount=${amount}`;
        }

        // Get the modal
        var modal = document.getElementById('id01');

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
    <!-- End Script -->
</body>

</html>