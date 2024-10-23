<?php
include 'db.php'; // Include your database connection

if (isset($_GET['url'])) {
    $productUrl = $_GET['url'];

    // Extract the product ID from the URL
    $productId = extractProductId($productUrl);

    // Check if the product ID was successfully extracted
    if (!$productId) {
        echo json_encode(['error' => 'Invalid product URL']);
        exit;
    }

    try {
        // Query the database to fetch product details
        $stmt = $pdo->prepare("SELECT title, description, price, rating, reviews, total_purchases FROM products WHERE product_id = ?");
        $stmt->execute([$productId]);

        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            echo json_encode($product);
        } else {
            echo json_encode(['error' => 'Product not found']);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'No URL provided']);
}

// Function to extract product ID from the URL
function extractProductId($url) {
    // Example: Assume the URL format is something like:
    // "https://www.flipkart.com/product_id_here"
    // Modify this logic based on the actual URL format.
    
    // Use regex to extract product ID if it's part of the URL
    $pattern = '/\/([A-Za-z0-9-]+)$/'; // Adjust pattern according to actual URL structure
    preg_match($pattern, $url, $matches);
    
    return isset($matches[1]) ? $matches[1] : null;
}
?>
