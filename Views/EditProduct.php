<?php
session_start();
ob_start();
include_once('../config.php');
include_once('../database/SQLiteConnection.php');
include_once('../Model/Product.php');

// Validate logged in
include_once('../config.php');
if (isset($_COOKIE['token']) && isset($_COOKIE['userLoggedIn'])) {
    if ((md5($_COOKIE['userLoggedIn'] . SECRET_WORD)) !== $_COOKIE['token'] || $_SESSION['role'] !== 'admin') {
        setcookie('message', 'Prohibited', time() + 3600, '/');
        header("location: /Views/Login.php");
    }
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
    // Sqlite conn
    $role = $_SESSION['role'];
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
    <link rel="stylesheet" href="../Assets/css/createProduct.css" />
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
        <div class="wrapper">
            <h3 class="title">EDIT DORAYAKI</h3>
            <form class="form" action="../Middlewares/edit.php" method="POST" enctype="multipart/form-data">

                <div class="input-field">
                    <label for="name">Product Variant</label>
                    <input type="text" id="name" name="name" placeholder="Variant of product here .." maxlength="255" value="<?php echo $item['name'] ?>" required>
                </div>

                <div class="input-field">
                    <label for="description">Description</label>
                    <textarea class="textarea" name="description" id="description" cols="30" rows="5" maxlength="255" placeholder="Description goes here ..." required><?php echo $item['description'] ?> </textarea>
                </div>

                <div class="input-field">
                    <label for="price">Price (Rupiah) </label>
                    <input type="number" min='0' step="100" id="price" name="price" value="<?php echo $item['price'] ?>" required>
                </div>

                <div class="input-field">
                    <label for="price">Stock</label>
                    <input type="number" min='0' id="stock" name="stock" value="<?php echo $item['stock'] ?>" required>
                </div>

                <div class="input-field">
                    <label for="file">Image</label>
                    <input class="upload" type="file" id="file" name="file" accept=".png,.jpg,.jpeg">
                    <label for="file">Leave blank if will not change</label>
                </div>

                <input type="hidden" id="id" name="id" value="<?php echo $id ?>">

                <div class="input-field">
                    <input class="button" type="submit" id="submit" name="edit" value="Update">
                </div>

            </form>
        </div>
    </div>
    <!-- end content -->


    <!-- Footer -->
    <?php include '../partials/footer.php'; ?>
    <!-- End Footer -->


</body>

</html>