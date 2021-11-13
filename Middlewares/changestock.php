<?php
session_start();
ob_start();
include_once('../database/SQLiteConnection.php');
include_once('../Model/Product.php');
include_once('../Model/History.php');
include_once('../Model/User.php');
include_once('../config.php');

// Validate logged in
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

if (isset($_POST['change'])) {

    $databasePath = '../database/' . DATABASE_NAME . '.sqlite';
    $pdo = (new SQLiteConnection())->connect($databasePath);
    $Product = new Product($pdo);
    $History = new History($pdo);
    $User = new User($pdo);
    $currentProduct = $Product->whereId($_POST['id'])[0];

    $changedAmount = $_POST['stock'];

    if ($pdo != null) {
        if ($changedAmount < 0) {
            $newStock = $currentProduct['stock'] - $changedAmount;
            if ($newStock < 0) {
                setcookie('message', 'Variant ' . $currentProduct['name'] . ' stock failed to be changed (amount cannot be less than 0)', time() + 3600, '/');
                header("location: /index.php");
            } else {
                $bool = $Product->changeStock($currentProduct['id'], $newStock);
                if ($bool) {
                    $users = $User->whereUsername($_SESSION['username'])[0];
                    $history = [
                        'user_id' => $users['id'],
                        'username' => $users['username'],
                        'product_id' => $currentProduct['id'],
                        'product_name' => $currentProduct['name'],
                        'quantity'  => $changedAmount,
                        'total_price' => null
                    ];
                    $History->insert($history);
                    setcookie('message', 'Variant ' . $currentProduct['name'] . ' stock successfully changed ', time() + 3600, '/');
                    header("location: /index.php");
                } else {
                    setcookie('message', 'Variant ' . $currentProduct['name'] . ' stock failed to be changed ', time() + 3600, '/');
                    header("location: /index.php");
                }
            }
        } else {
            $newStock = $currentProduct['stock'] + $changedAmount;
            $bool = $Product->changeStock($currentProduct['id'], $newStock);
            if ($bool) {
                $users = $User->whereUsername($_SESSION['username'])[0];
                $history = [
                    'user_id' => $users['id'],
                    'username' => $users['username'],
                    'product_id' => $currentProduct['id'],
                    'product_name' => $currentProduct['name'],
                    'quantity'  => $changedAmount,
                    'total_price' => null
                ];
                $History->insert($history);
                setcookie('message', 'Variant ' . $currentProduct['name'] . ' stock successfully changed ', time() + 3600, '/');
                header("location: /index.php");
            } else {
                setcookie('message', 'Variant ' . $currentProduct['name'] . ' stock failed to be changed ', time() + 3600, '/');
                header("location: /index.php");
            }
        }
    } else {
        // Fail to connect
        header("location: /index.php");
    }
} else {
    header("location: /index.php");
}
