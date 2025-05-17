<?php
$sql = 'SELECT * FROM categories';
$stmt = $database->query($sql);
$categories = $stmt->fetchAll();
?>

<!-- admin_category start -->
<div class="admin container">
    <div class="title">
        <h2>Категории</h2>
    </div>

    <div class="admin_content">
        <div class="admin_fyltr">
            <a href="./?page=admin_category">Категории</a>
            <a href="./?page=admin_zakaz">Заказы</a>
            <a href="./?page=admin_tovar">Товары</a>
            <a href="./?page=admin_person">Пользователи</a>
        </div>

        <div class="admin_category_right">
            <div class="categories_katalog">
                <?php foreach ($categories as $category): ?>
                    <a href="./?page=admin_subcategories&id_category=<?= $category['id'] ?>">
                        <div class="category_card">
                            <p><?= htmlspecialchars($category['title']) ?></p>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<!-- admin_category end -->