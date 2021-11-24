<?php
include_once('../database/SQLiteConnection.php');
include_once('../Model/Product.php');
include_once('../config.php');
$databasePath = '../database/' . DATABASE_NAME . '.sqlite';
$pdo = (new SQLiteConnection())->connect($databasePath);
$Product = new Product($pdo);
$wsdl   = "http://localhost:9999/dorayaki?wsdl";
$client = new SoapClient($wsdl, array('trace' => 1));  // The trace param will show you errors stack
// web service input params
$request_param = array(
    "getRequestStock" => array()
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