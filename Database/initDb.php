<?php
include_once("../config.php");
ob_start();

$tables = [
    'users' => 'DROP TABLE IF EXISTS users; CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY NOT NULL,
        email TEXT NOT NULL,
        username TEXT UNIQUE NOT NULL,
        password TEXT NOT NULL,
        role TEXT NOT NULL
    );',
    'products' => 'DROP TABLE IF EXISTS products; CREATE TABLE IF NOT EXISTS products (
        id INTEGER PRIMARY KEY NOT NULL,
        name TEXT NOT NULL UNIQUE,
        description TEXT,
        price INTEGER NOT NULL,
        stock INTEGER NOT NULL,
        image TEXT,
        sold INTEGER
    );',
    'histories' => 'DROP TABLE IF EXISTS histories; CREATE TABLE IF NOT EXISTS histories (
        id INTEGER PRIMARY KEY NOT NULL,
        user_id INTEGER,
        username TEXT,
        product_id INTEGER,
        product_name TEXT,
        quantity INTEGER,
        total_price INTEGER,
        date TEXT,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
    );',
];

function initDatabase (PDO $pdo, array $tables){
    foreach ($tables as $tablesName => $query){
        $pdo->exec($query);
    }
}

try {
    $db = new PDO('sqlite:' . DATABASE_NAME.  '.sqlite');
    initDatabase($db, $tables);
    echo 'database initialized';

    $query = 'INSERT INTO users (email, username, password, role) VALUES (:email, :username, :password, :role);';
    $stmt = $db->prepare($query);
    $stmt->execute([
        ':email' => 'admin@admin.com',
        ':username' => 'admin',
        ':password' => password_hash('admin123', PASSWORD_DEFAULT),
        ':role'  => 'admin'
    ]);
    $stmt->execute([
        ':email' => 'user@user.com',
        ':username' => 'user',
        ':password' => password_hash('user123', PASSWORD_DEFAULT),
        ':role'  => 'user'
    ]);
} catch (PDOException $e) {
    echo $e->getMessage();
}

?>