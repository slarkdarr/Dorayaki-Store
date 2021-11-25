<?php
include_once('../database/SQLiteConnection.php');
include_once('../Model/Product.php');
include_once('../config.php');

// Function to get the client IP address
function get_client_ip() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

$databasePath = '../database/' . DATABASE_NAME . '.sqlite';
$pdo = (new SQLiteConnection())->connect($databasePath);
$Product = new Product($pdo);
$wsdl   = "http://localhost:9999/dorayaki?wsdl";
$client = new SoapClient($wsdl, array('trace' => 1));  // The trace param will show you errors stack
// web service input params
$request_param = array(
    "getRequestStock" => array(
        "arg0" => get_client_ip()
    )
);
try {
    // $responce_param = $client->webservice_methode_name($request_param);
    $listRequest =  $client->__soapCall("getRequestStock", $request_param)->return; // Alternative way to call soap method
    $listRequest = json_decode($listRequest, true);

    if (is_null($listRequest)){
       echo "No data";
    } else {
        foreach($listRequest as $val){
            $bool = $Product->changeStockByName($val['recipe_name'], $val['quantity']);
            if ($bool) {
                echo "Query update success for " . $val['recipe_name'] . " " . $val['quantity'];
            } else{
                echo "Failed ". $val['recipe_name'] . " " . $val['quantity'];
            }
        }
    }
    
} catch (Exception $e) {
    echo "<h2>Exception Error!</h2>";
    echo $e->getMessage();
}