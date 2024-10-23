<?php
require 'db_connect.php';

header('Content-Type: application/json');

// Retrieve search parameters
$title = isset($_GET['title']) ? $_GET['title'] : '';
$min_price = isset($_GET['min_price']) ? floatval($_GET['min_price']) : 0;
$max_price = isset($_GET['max_price']) ? floatval($_GET['max_price']) : PHP_INT_MAX;

try {
    // Build the SQL query with parameters
    $sql = "SELECT * FROM products WHERE title LIKE :title AND current_price BETWEEN :min_price AND :max_price ORDER BY created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'title' => '%' . $title . '%',
        'min_price' => $min_price,
        'max_price' => $max_price
    ]);

    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // For each product, fetch its price history
    foreach ($products as &$product) {
        $history_stmt = $pdo->prepare("SELECT price, checked_at FROM price_history WHERE product_id = :id ORDER BY checked_at DESC");
        $history_stmt->execute(['id' => $product['id']]);
        $price_history = $history_stmt->fetchAll(PDO::FETCH_ASSOC);
        $product['price_history'] = $price_history;
    }

    echo json_encode(['products' => $products]);

} catch (PDOException $e) {
    echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
}
?>