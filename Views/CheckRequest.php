<?php
session_start();
ob_start();
// Validate logged in
include_once('../config.php');
if (isset($_COOKIE['token']) && isset($_COOKIE['userLoggedIn'])) {
    if ((md5($_COOKIE['userLoggedIn'] . SECRET_WORD)) !== $_COOKIE['token'] || $_SESSION['role'] !== 'admin') {
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
$wsdl   = "http://localhost:9999/dorayaki?wsdl";
$client = new SoapClient($wsdl, array('trace' => 1));  // The trace param will show you errors stack

// web service input params
$request_param = array(
    "getRequestStock" => array()
);
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
        <?php
        try {
            // $responce_param = $client->webservice_methode_name($request_param);
            $listRequest =  $client->__soapCall("getRequestStock", $request_param)->return; // Alternative way to call soap method
            $listRequest = json_decode($listRequest, true);
            if (is_null($listRequest)){
               echo "No data";
            } else {
                echo "<pre>";
                var_dump($listRequest);
                echo ("</pre>");
            }
            
        } catch (Exception $e) {
            echo "<h2>Exception Error!</h2>";
            echo $e->getMessage();
        }

        ?>
    </div>
    <!-- end content -->

    <!-- Footer -->
    <?php include '../partials/footer.php'; ?>
    <!-- End Footer -->
</body>

</html>