<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = (int)$_POST['delete_id'];
    if ($delete_id > 0) {
        try {
            $sql = "DELETE FROM subcategories WHERE id = :id";
            $stmt = $database->prepare($sql);
            $stmt->bindParam(':id', $delete_id);

            if ($stmt->execute()) {

                $redirect_url = "./?page=admin_subcategories&id_category=" . ($_GET['id_category'] ?? '');
                header("Location: " . $redirect_url);
                exit;
            }
        } catch (PDOException $e) {
            $error = "Ошибка при удалении: " . $e->getMessage();
        }
    }
}

$category_id = isset($_GET['id_category']) ? (int)$_GET['id_category'] : 0;

$category = null;
if ($category_id) {
    try {
        $sql = "SELECT * FROM categories WHERE id = :id";
        $stmt = $database->prepare($sql);
        $stmt->bindParam(':id', $category_id);
        $stmt->execute();
        $category = $stmt->fetch();
    } catch (PDOException $e) {
        die("Ошибка при получении категории: " . $e->getMessage());
    }
}

try {
    $sql = "SELECT * FROM subcategories WHERE id_category = :id_category ORDER BY title";
    $stmt = $database->prepare($sql);
    $stmt->bindParam(':id_category', $category_id);
    $stmt->execute();
    $subcategories = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Ошибка при получении подкатегорий: " . $e->getMessage());
}
?>

<div class="admin container">
    <div class="title">
        <h2>Подкатегории</h2>
        <p>Категория: <?= isset($category['title']) ? htmlspecialchars($category['title']) : 'Неизвестная категория' ?></p>
    </div>

    <?php if (isset($error)): ?>
        <div class="error-message"><?= $error ?></div>
    <?php endif; ?>

    <div class="admin_content">
        <div class="admin_fyltr">
            <a href="./?page=admin_category">Категории</a>
            <a href="admin_zakaz.php">Заказы</a>
            <a href="admin_tovar.php">Товары</a>
            <a href="admin_person.php">Пользователи</a>
        </div>

        <div class="admin_category_right">
            <a href="./?page=add_admin_subcategories&id_category=<?= $category_id ?>"><button class="btn_pink">Добавить подкатегорию</button></a>
            <div class="categories_katalog">
                <?php if (empty($subcategories)): ?>
                    <p>Нет подкатегорий</p>
                <?php else: ?>
                    <?php foreach ($subcategories as $subcat): ?>
                        <div class="category_card">
                            <p><?= htmlspecialchars($subcat['title']) ?></p>
                            <form method="POST" class="delete-form">
                                <input type="hidden" name="delete_id" value="<?= $subcat['id'] ?>">

                                <a href="./?page=edit_admin_subcategories&id_category=<?=$category_id?>&id=<?= $subcat['id'] ?>">Редактировать</a>

                                <button type="submit" class="delete-btn"
                                    onclick="return confirm('Вы уверены, что хотите удалить эту подкатегорию?')">
                                    <img src="assets/img/admin/korzina.svg" alt="Удалить">
                                    <span>Удалить</span>
                                </button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <a href="?page=admin_category">Назад →</a>
        </div>
    </div>
</div>

<style>
    .category_card {
        border: 1px solid #ddd;
        background: #f9f9f9;
        width: 200px;
        flex-direction: column;
        gap: 10px;
    }

    .delete-form {
        margin-top: 10px;
    }

    .delete-btn {
        background: none;
        border: none;
        color: #f44336;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 5px;
    }

</style>