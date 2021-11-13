<?php
session_start();
ob_start();
include_once('../database/SQLiteConnection.php');
include_once('../Model/Product.php');
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

if (isset($_GET['id'])) {

    $databasePath = '../database/' . DATABASE_NAME . '.sqlite';
    $pdo = (new SQLiteConnection())->connect($databasePath);
    $Product = new Product($pdo);
    $rowCount = $Product->deleteById($_GET['id']);

    if ($rowCount > 0) {
        setcookie('message', 'Variant with id ' . $_GET['id'] . ' deleted successfully', time() + 3600, '/');
        header("location: /index.php");
    } else {
        setcookie('message', 'Variant with id' . $_GET['id'] . ' fail to deleted', time() + 3600, '/');
        header("location: /index.php");
    }
} else {
    header("location: /index.php");
}
