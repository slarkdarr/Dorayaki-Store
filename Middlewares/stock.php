<?php
ob_start();
session_start();
include_once('../database/SQLiteConnection.php');
include_once('../Model/Product.php');
include_once('../config.php');

// Validate logged in
if (isset($_COOKIE['token']) && isset($_COOKIE['userLoggedIn'])) {
    if ((md5($_COOKIE['userLoggedIn'] . SECRET_WORD)) !== $_COOKIE['token']) {
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
header('Content-Type: application/json; charset=utf-8');
echo json_encode($item);
?>
