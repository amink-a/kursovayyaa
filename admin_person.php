<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['block_id'])) {
    $block_id = (int) $_POST['block_id'];
    if ($block_id > 0) {
        try {
            // Get current block status
            $sql = "SELECT is_blocked FROM users WHERE id = :id";
            $stmt = $database->prepare($sql);
            $stmt->bindParam(':id', $block_id);
            $stmt->execute();
            $user = $stmt->fetch();
            
            // Toggle block status
            $new_status = $user['is_blocked'] ? 0 : 1;
            
            $sql = "UPDATE users SET is_blocked = :is_blocked WHERE id = :id";
            $stmt = $database->prepare($sql);
            $stmt->bindParam(':is_blocked', $new_status);
            $stmt->bindParam(':id', $block_id);
            $stmt->execute();

            // Перенаправляем на эту же страницу
            header("Location: ./?page=admin_person");
            exit;
        } catch (PDOException $e) {
            $error = "Ошибка при блокировке: " . $e->getMessage();
        }
    }
}

$sql = "SELECT * FROM users";
$adminUsers = $database->query($sql)->fetchAll();

?>

<!-- admin_person start -->
<div class="admin container">

    <div class="title">
        <h2>Пользователи</h2>
    </div>

    <div class="admin_content">
        <div class="admin_fyltr">
            <a href="./?page=admin_category">Категории</a>
            <a href="./?page=admin_zakaz">Заказы</a>
            <a href="./?page=admin_tovar">Товары</a>
            <a href="./?page=admin_person">Пользователи</a>
        </div>

        <div class="admin_prosmotors">
            <input type="text" placeholder="Поиск...">

            <div class="admin_prosmotors_catalog">
                <?php foreach ($adminUsers as $user): ?>
                    <div class="admin_prosmotors_card">
                        <p><?= $user['id'] ?></p>
                        <p><?= $user['name'] ?></p>
                        <p><?= $user['email'] ?></p>
                        <div class="delete">
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="block_id" value="<?= $user['id'] ?>">
                                <button type="submit" class="<?= $user['is_blocked'] ? 'unblock-btn' : 'block-btn' ?>">
                                    <img src="assets/img/admin/korzina.svg" alt="">
                                    <span><?= $user['is_blocked'] ? 'Разблокировать' : 'Заблокировать' ?></span>
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<!-- admin_person end -->

<style>
.block-btn {
    background: none;
    border: 1px solid #f44336;
    color: #f44336;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 5px;
    padding: 8px 12px;
    border-radius: 4px;
    transition: all 0.3s ease;
}

.block-btn:hover {
    background-color: #f44336;
    color: white;
}

.unblock-btn {
    background: none;
    border: 1px solid #4CAF50;
    color: #4CAF50;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 5px;
    padding: 8px 12px;
    border-radius: 4px;
    transition: all 0.3s ease;
}

.unblock-btn:hover {
    background-color: #4CAF50;
    color: white;
}

.block-btn img, .unblock-btn img {
    width: 16px;
    height: 16px;
    filter: brightness(0) saturate(100%);
}

.block-btn:hover img {
    filter: brightness(0) invert(1);
}

.unblock-btn:hover img {
    filter: brightness(0) invert(1);
}
</style>