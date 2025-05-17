<?php
// Проверяем, была ли отправлена форма
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'])) {
    $title = trim($_POST['title']);
    $category_id = isset($_GET['id_category']) ? (int)$_GET['id_category'] : 0;

    if (!empty($title) && $category_id > 0) {
        try {
            $sql = "INSERT INTO subcategories (title, id_category) VALUES (:title, :id_category)";
            $stmt = $database->prepare($sql);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':id_category', $category_id);
            
            if ($stmt->execute()) {
                // Перенаправляем обратно к списку подкатегорий
                header("Location: ?page=admin_subcategories&id_category=" . $category_id);
                exit;
            }
        } catch (PDOException $e) {
            $error = "Ошибка при добавлении подкатегории: " . $e->getMessage();
        }
    } else {
        $error = "Пожалуйста, заполните название подкатегории";
    }
}

// Получаем информацию о категории
$category_id = isset($_GET['id_category']) ? (int)$_GET['id_category'] : 0;
$category = null;

if ($category_id > 0) {
    try {
        $sql = "SELECT title FROM categories WHERE id = :id";
        $stmt = $database->prepare($sql);
        $stmt->bindParam(':id', $category_id);
        $stmt->execute();
        $category = $stmt->fetch();
    } catch (PDOException $e) {
        die("Ошибка при получении категории: " . $e->getMessage());
    }
} else {
    die("Не указана категория");
}
?>

<!-- добавить подкатегорию start -->
<div class="edit container">
    <div class="title">
        <h2>Добавить подкатегорию</h2>
    </div>

    <?php if (isset($error)): ?>
        <div class="error-message"><?= $error ?></div>
    <?php endif; ?>

    <div class="forma_osnova">
        <form action="" method="post">
            <input type="text" name="title" placeholder="Название подкатегории" required>
            
            <button class="btn_pink" type="submit">Добавить</button>
            <a href="?page=admin_subcategories&id_category=<?= $category_id ?>" class="btn_gray">Назад →</a>
        </form>
    </div>
</div>
<!-- добавить подкатегорию end -->