<?php
session_start();
ob_start();
include_once('../database/SQLiteConnection.php');
include_once('../Model/User.php');
include_once('../config.php');


if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $newUser = [
        'email' => $_POST['email'],
        'username' => $_POST['username'],
        'password' =>  $_POST['password'],
        'role' =>  'user',
];
    $databasePath = '../database/' . DATABASE_NAME . '.sqlite';
    $pdo = (new SQLiteConnection())->connect($databasePath);

    if ($pdo != null) {
        $User = new User($pdo);
        $userData = $User->whereUsername($username);

        if (count($userData) !== 0) {
            setcookie('message', 'Username already exists!', time() + 3600 * 24, '/');
            echo $_COOKIE['message'];
            header("location: /Views/Register.php");
        } else {
            $User->insert($newUser);
            setcookie('message', `Register success! Welcome $username!`, time() + 3600 * 24, '/');
            // Generate token
            setcookie('userLoggedIn', $username, time() + 3600, '/');
            setcookie('token', md5($username . SECRET_WORD) , time() + 3600, '/');
            // Save username and role to session
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $newUser['role'];
            header("location: /index.php");
        }
    } else {
        echo "Couldn't connect to the SQLite Database!";
    }
}
