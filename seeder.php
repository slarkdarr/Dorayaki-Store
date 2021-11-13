<?php
ob_start();
include_once('config.php');
include_once('Database/SQLiteConnection.php');
include_once('Model/User.php');
include_once('Model/Product.php');
include_once('Model/History.php');

$databasePath = 'database/' . DATABASE_NAME . '.sqlite';
$pdo = (new SQLiteConnection())->connect($databasePath);

if ($pdo != null) {
    $User = new User($pdo);
    $userData = $User->get();
    $userData = $User->whereId(1);

    // Product seeder
    $Product = new Product($pdo);
    for ($i = 0; $i < 25; $i++){
        $newProduct = [
            'name' => 'dorayaki ' . $i,
            'description' => 'dorayaki kesukaan doraemon',
            'price' => 35000,
            'stock' => 100,
            'image' => 'Assets/uploads/dorayaki.jpg'
        ];
        $Product->insert($newProduct);
    }

    $products = $Product->get();
    $history = new History($pdo);
    $counter = 0;
    foreach($products as $product) {
        $quantity = rand(1,10);
        $user_id = rand(1,2);
        $username = $user_id == 1 ? 'admin' : 'user';
        $newHistory = [
            'user_id' => $user_id,
            'username' => $username,
            'product_id' => $product['id'],
            'product_name' => $product['name'],
            'quantity' => $quantity,
            'total_price' => $product['price'] * $quantity,
        ];
        $history->insert($newHistory);
        $counter += 1;
        if ($counter == 10){
            break;
        }
    }
} else
    echo 'Whoops, could not connect to the SQLite database!';
?>

<body>
    <p>Seed successfully!</p>

</body>