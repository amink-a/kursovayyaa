<?php
// Обработка добавления в корзину
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
        $product_id = $_POST['product_id'];

        // Получаем информацию о товаре для уведомления
        $product = $database->query("SELECT * FROM products WHERE id = $product_id")->fetch();

        $sql = "SELECT * FROM carts WHERE product_id = $product_id AND user_id = $user_id";
        $cart = $database->query($sql)->fetch();

        if ($cart) {
            $sql = "UPDATE carts SET count = count + 1 WHERE id = " . $cart['id'];
            $database->query($sql);
        } else {
            $sql = "INSERT INTO carts (product_id, user_id, count) VALUES ($product_id, $user_id, 1)";
            $database->query($sql);
        }

        // Сохраняем данные товара для отображения в уведомлении
        $_SESSION['last_added_product'] = [
            'image' => $product['image'],
            'title' => $product['title'],
            'price' => $product['price'],
            'price_skidka' => $product['price_skidka'],
            'time' => time()
        ];

        // Перенаправляем на ту же страницу без параметров POST
        header('Location: ./?page=katalog');
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
        $delete_id = $_POST['delete_id'];
        if (!empty($delete_id)) {
            unlink($product['image']);
            $sql = "DELETE FROM products WHERE id =:id";
            $stmt = $database->prepare($sql);
            $stmt->bindParam(':id', $delete_id);

            if ($stmt->execute()) {
                header('Location: ./?page=katalog');
                exit;                       
            } else {
                echo 'Ошибка удаления';
            }
        }
    }
} else {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
        $_SESSION['need_login'] = true;
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

// Получаем выбранную категорию и подкатегорию из GET-параметров
$selected_category = isset($_GET['category']) ? (int) $_GET['category'] : null;
$selected_subcategory = isset($_GET['subcategory']) ? (int) $_GET['subcategory'] : null;

// Формируем SQL-запрос с учетом фильтрации
$sql = "SELECT * FROM products WHERE 1=1";
$params = [];

if ($selected_category) {
    $sql .= " AND category_id = :category_id";
    $params[':category_id'] = $selected_category;
}

if ($selected_subcategory) {
    $sql .= " AND subcategory_id = :subcategory_id";
    $params[':subcategory_id'] = $selected_subcategory;
}

$stmt = $database->prepare($sql);
$stmt->execute($params);
$cards = $stmt->fetchAll();

// Получаем все категории и подкатегории
$categories = $database->query("SELECT * FROM categories")->fetchAll();
$all_subcategories = $database->query("SELECT * FROM subcategories")->fetchAll();

?>

<?php if (isset($_SESSION['last_added_product'])): ?>
    <div id="cart-notification" class="notification">
        <img src="<?= $_SESSION['last_added_product']['image'] ?>" alt="<?= $_SESSION['last_added_product']['title'] ?>">
        <div class="notification-content">
            <div class="notification-title"><?= $_SESSION['last_added_product']['title'] ?></div>
            <div class="notification-prices">
                <span class="notification-price"><?= number_format($_SESSION['last_added_product']['price'], 0, '', ' ') ?>
                    ₽</span>
                <span
                    class="notification-price-sale"><?= number_format($_SESSION['last_added_product']['price_skidka'], 0, '', ' ') ?>
                    ₽</span>
            </div>
            <div class="notification-message">Товар добавлен в корзину</div>
        </div>
        <button class="notification-close" onclick="hideNotification()">×</button>
    </div>
<?php endif; ?>

<!-- katalog start -->
<div class="banner_katalog">
    <div class="container">
        <div class="perehod_katalog">
            <a href="./">Главная</a>
            <p>|</p>
            <p>Каталог</p>
        </div>
    </div>
</div>

<div class="katalog_cards container">
    <div class="title">
        <h2>Все товары</h2>
    </div>

    <div class="katalog_with_fyltr">
        <div class="fyltr_ktalog">
            <h5>Фильтры</h5>

            <!-- Ссылка "Все категории" -->
            <a href="?page=katalog">
                <p class="category_katalog_nazv">Все категории</p>
            </a>

            <!-- Список категорий -->
            <?php foreach ($categories as $cat): ?>
                <?php
                // Получаем подкатегории для текущей категории
                $subcategories = array_filter($all_subcategories, function ($subcat) use ($cat) {
                    return $subcat['id_category'] == $cat['id'];
                });
                ?>

                <?php if (empty($subcategories)): ?>
                    <!-- Если нет подкатегорий, выводим просто категорию -->
                    <a href="?page=katalog&category=<?= $cat['id'] ?>">
                        <button class="accordion"><?= htmlspecialchars($cat['title']) ?></button>
                    </a>
                <?php else: ?>
                    <!-- Если есть подкатегории, делаем аккордеон -->
                    <button class="accordion"><?= htmlspecialchars($cat['title']) ?></button>
                    <div class="panel">
                        <a href="?page=katalog&category=<?= $cat['id'] ?>">Все</a>
                        <?php foreach ($subcategories as $subcat): ?>
                            <a href="?page=katalog&category=<?= $cat['id'] ?>&subcategory=<?= $subcat['id'] ?>">
                                <?= htmlspecialchars($subcat['title']) ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

        <div class="cardss">
            <div class="osnova_kaatlog">
                <?php if (empty($cards)): ?>
                    <p>Товары не найдены</p>
                <?php else: ?>
                    <?php foreach ($cards as $card): ?>
                        <!-- Вывод карточек товаров -->
                        <a href="./?page=odin_tovar&id=<?= $card['id'] ?>">
                            <div class="osnov_card">
                                <div class="image_osn_card">
                                    <img class="image_tovar" src="<?= $card['image'] ?>" alt="Карточка товара">
                                    <form action="" method="post">
                                        <input type="hidden" name="add_to_cart" value="1">
                                        <input type="hidden" name="product_id" value="<?= $card['id'] ?>">
                                        <?php
                                        // Проверяем, есть ли товар в корзине
                                        $in_cart = false;
                                        if (isset($_SESSION['user_id'])) {
                                            $stmt = $database->prepare("SELECT * FROM carts WHERE user_id = :user_id AND product_id = :product_id");
                                            $stmt->execute(['user_id' => $_SESSION['user_id'], 'product_id' => $card['id']]);
                                            $in_cart = $stmt->fetch() ? true : false;
                                        }
                                        ?>
                                        <button type="submit" class="korzina_button <?= $in_cart ? 'in-cart' : '' ?>">
                                        </button>
                                    </form>
                                </div>
                                <div class="opisanie_osnov_card">
                                    <?php
                                    $stmt = $database->prepare('SELECT * FROM subcategories WHERE id = :id');
                                    $stmt->execute(['id' => $card['subcategory_id']]);
                                    $subcategory = $stmt->fetch();
                                    ?>
                                    <p class="category_osnov_card"><?= $subcategory['title'] ?></p>
                                    <p class="name_osnov_card"><?= $card['title'] ?></p>
                                    <div class="cost_with_skidka">
                                        <p class="cost"><?= number_format($card['price'], 0, '', ' ') ?> ₽</p>
                                        <p class="skidka"><?= number_format($card['price_skidka'], 0, '', ' ') ?> ₽</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <?php if (!empty($cards)): ?>
                <a href="#"><button class="button_pr">Смотреть еще →</button></a>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        padding: 15px;
        display: flex;
        align-items: center;
        gap: 15px;
        z-index: 1000;
        transform: translateX(120%);
        transition: transform 0.3s ease-in-out;
        max-width: 350px;
    }

    .notification.show {
        transform: translateX(0);
    }

    .notification img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 4px;
    }

    .notification-content {
        flex: 1;
    }

    .notification-title {
        font-weight: bold;
        margin-bottom: 5px;
    }

    .notification-prices {
        display: flex;
        gap: 10px;
        margin-bottom: 5px;
    }

    .notification-price {
        color: #888;
        text-decoration: line-through;
    }

    .notification-price-sale {
        color: #ff3b30;
        font-weight: bold;
    }

    .notification-message {
        color: #4CAF50;
        font-size: 14px;
    }

    .notification-close {
        background: none;
        border: none;
        cursor: pointer;
        font-size: 18px;
        color: #888;
    }

    @keyframes fadeOut {
        from {
            opacity: 1;
        }

        to {
            opacity: 0;
        }
    }

    .fade-out {
        animation: fadeOut 0.5s ease-in-out forwards;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        <?php if (isset($_SESSION['last_added_product']) && (time() - $_SESSION['last_added_product']['time'] < 2)): ?>
            showNotification();
            setTimeout(function () {
                hideNotification();
            }, 3000);
            <?php
            unset($_SESSION['last_added_product']);
        endif; ?>

        <?php if (isset($_SESSION['need_login'])): ?>
            alert('Войдите в аккаунт, чтобы добавлять товары в корзину');
            <?php unset($_SESSION['need_login']); ?>
        <?php endif; ?>
    });

    function showNotification() {
        const notification = document.getElementById('cart-notification');
        notification.classList.add('show');
    }

    function hideNotification() {
        const notification = document.getElementById('cart-notification');
        notification.classList.add('fade-out');
        setTimeout(function () {
            notification.classList.remove('show', 'fade-out');
        }, 500);
    }
</script>