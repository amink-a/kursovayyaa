<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = (int) $_POST['delete_id'];
    if ($delete_id > 0) {
        try {
            $sql = "DELETE FROM products WHERE id = :id";
            $stmt = $database->prepare($sql);
            $stmt->bindParam(':id', $delete_id);
            $stmt->execute();

            // Перенаправляем на эту же страницу
            header("Location: ./?page=admin_tovar");
            exit;
        } catch (PDOException $e) {
            $error = "Ошибка при удалении: " . $e->getMessage();
        }
    }
}

$sql = "SELECT * FROM products";
$adminProducts = $database->query($sql)->fetchAll();

?>

<!-- admin_tovar start -->
<div class="admin container">

    <div class="title">
        <h2>Товары</h2>
    </div>

    <div class="admin_content">
        <div class="admin_fyltr">
            <a href="./?page=admin_category">Категории</a>
            <a href="./?page=admin_zakaz">Заказы</a>
            <a href="./?page=admin_tovar">Товары</a>
            <a href="./?page=admin_person">Пользователи</a>
        </div>

        <div class="admin_prosmotors_tovar">
            <div class="admin_prosmotors">
                <input type="text" placeholder="Поиск...">

                <a href="./?page=create"><button class="btn_pink" type="submit">Добавить товар</button></a>

                <div class="admin_prosmotors_catalog">
                    <?php foreach ($adminProducts as $product): ?>
                        <div class="admin_prosmotors_card">
                            <img class="tovarr" src="<?= $product['image'] ?>" alt="">
                            <p><?= $product['id'] ?></p>
                            <p><?= $product['title'] ?></p>
                            <div class="add">
                                <img src="assets/img/admin/add.svg" alt="">
                                <a href="./?page=edit&id=<?= $product['id'] ?>">Редактировать</a>
                            </div>
                            <div class="delete">
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="delete_id" value="<?= $product['id'] ?>">
                                    <button type="submit">
                                        <img src="assets/img/admin/korzina.svg" alt="">
                                        <span>Удалить</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- admin_tovar end -->