<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Войдите в аккаунт']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    try {
        $product_id = (int)$_POST['product_id'];
        $user_id = $_SESSION['user_id'];

        // Check if product exists in cart
        $sql = "SELECT * FROM carts WHERE product_id = :product_id AND user_id = :user_id";
        $stmt = $database->prepare($sql);
        $stmt->execute(['product_id' => $product_id, 'user_id' => $user_id]);
        $cart = $stmt->fetch();

        if ($cart) {
            // Update existing cart item
            $sql = "UPDATE carts SET count = count + 1 WHERE id = :id";
            $stmt = $database->prepare($sql);
            $stmt->execute(['id' => $cart['id']]);
        } else {
            // Add new cart item
            $sql = "INSERT INTO carts (product_id, user_id, count) VALUES (:product_id, :user_id, 1)";
            $stmt = $database->prepare($sql);
            $stmt->execute(['product_id' => $product_id, 'user_id' => $user_id]);
        }

        echo json_encode(['success' => true, 'message' => 'Товар добавлен в корзину']);
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Ошибка базы данных при добавлении товара']);
    } catch (Exception $e) {
        error_log("General error: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Произошла ошибка при добавлении товара']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Неверный запрос']);
} 