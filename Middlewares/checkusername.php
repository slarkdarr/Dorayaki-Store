<?php
ob_start();
include_once('../database/SQLiteConnection.php');
include_once('../Model/User.php');
include_once('../config.php');

$username = $_GET['username'];
$databasePath = '../database/' . DATABASE_NAME . '.sqlite';
$pdo = (new SQLiteConnection())->connect($databasePath);

if ($pdo != null) {
    $User = new User($pdo);
    $userData = $User->whereUsername($username);
    echo json_encode($userData);
} else {
    echo 'false';
}
