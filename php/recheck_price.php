<?php
require 'db_connect.php';

header('Content-Type: application/json');

try {
    // Fetch all products
    $stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
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