<?php
echo 'кутак баш';
// Получаем ID подкатегории
$subcategory_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$category_id = isset($_GET['id_category']) ? (int) $_GET['id_category'] : 0;
echo $category_id;
// Загружаем данные подкатегории
$subcategory = null;
if ($subcategory_id > 0) {
    try {
        $sql = "SELECT * FROM subcategories WHERE id = :id";
        $stmt = $database->prepare($sql);
        $stmt->bindParam(':id', $subcategory_id);
        $stmt->execute();
        $subcategory = $stmt->fetch();
    } catch (PDOException $e) {
        die("Ошибка при получении подкатегории: " . $e->getMessage());
    }
}

// Обработка отправки формы
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'])) {
    $title = trim($_POST['title']);

    try {
        $sql = "UPDATE subcategories SET title = :title WHERE id = :id";
        $stmt = $database->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':id', $subcategory_id);

        if ($stmt->execute()) {
            header("Location: ./?page=admin_subcategories&id_category=" . $category_id);
            exit;
        }
    } catch (PDOException $e) {
        $error = "Ошибка при обновлении: " . $e->getMessage();
    }
}
?>

<div class="edit container">
    <div class="title">
        <h2>Редактировать подкатегорию</h2>
    </div>

    <?php if (isset($error)): ?>
        <div class="error-message"><?= $error ?></div>
    <?php endif; ?>

    <div class="forma_osnova">
        <form action="" method="post">
            <input type="text" name="title" placeholder="Название подкатегории"
                value="<?= isset($subcategory['title']) ? htmlspecialchars($subcategory['title']) : '' ?>" required>

            <button class="btn_pink" type="submit">Редактировать</button>
            <a href="?page=admin_subcategories&id_category=<?= $category_id ?>" class="btn_gray">Назад →</a>
        </form>
    </div>
</div>