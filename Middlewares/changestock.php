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
    $users = $User->whereUsername($_SESSION['username'])[0];

    if ($pdo != null) {
        $wsdl   = "http://localhost:9999/dorayaki?wsdl";
        $client = new SoapClient($wsdl, array('trace' => 1));  // The trace param will show you errors stack

        // web service input params
        $request_param = array(
            "postRequestStock" => array(    // The ads ID
                "arg0" => $currentProduct['name'],
                "arg1" => $changedAmount,
                "arg2" => $users['email'],
                "arg3" => $_SERVER['REMOTE_ADDR']
            )
        );

        try {
            // $responce_param = $client->webservice_methode_name($request_param);
            $response =  $client->__soapCall("postRequestStock", $request_param)->return; // Alternative way to call soap method
            $history = [
                'user_id' => $users['id'],
                'username' => $users['username'],
                'product_id' => $currentProduct['id'],
                'product_name' => $currentProduct['name'],
                'quantity'  => $changedAmount,
                'total_price' => null
            ];
            $History->insert($history);
            setcookie('message', 'Variant ' . $currentProduct['name'] . " " . $response, time() + 3600, '/');
            header("location: /index.php");
        } catch (Exception $e) {
            echo "<h2>Exception Error!</h2>";
            echo $e->getMessage();
        }
    } else {
        // Fail to connect
        header("location: /index.php");
    }
} else {
    header("location: /index.php");
}
