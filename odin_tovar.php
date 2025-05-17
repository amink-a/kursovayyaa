<?php
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM products WHERE id =:id";
    $stmt = $database->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $card = $stmt->fetch();

    if (!$card) {
        die('товар не найден');
    }

    $similar_products = $database->prepare("
        SELECT * FROM products 
        WHERE category_id = :category_id 
        AND id != :current_id 
        ORDER BY RAND() 
        LIMIT 4
    ");
    $similar_products->execute([
        ':category_id' => $card['category_id'],
        ':current_id' => $card['id']
    ]);
    $similar_products = $similar_products->fetchAll();

} else {
    die('id некорректен');
}

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

        // Если это AJAX-запрос, возвращаем JSON вместо редиректа
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'success']);
            exit();
        }

        // Стандартный редирект для обычных запросов
        header('Location: ./?page=odin_tovar&id=' . $_GET['id']);
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
                header('Location: ./?page=odin_tovar&id=' . $product_id);
                exit;
            } else {
                echo 'Ошибка удаления';
            }
        }
    }
} else {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
        $_SESSION['need_login'] = true;
        header('Location: ./?page=odin_tovar&id=' . $product_id);
        exit();
    }
}

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

<!-- odin_tovar start -->
<div class="odin_tovar container">

    <div class="odin_tovar_verh">
        <div class="odin_tovar_verh_left">
            <div class="odin_tovar_verh_left">
                <div class="many_slider_left">
                    <div class="images">
                        <img src="<?= $card['image'] ?>" alt="Изображение 1" class="thumbnail">
                        <img src="<?= $card['image2'] ?>" alt="Изображение 2" class="thumbnail">
                        <img src="<?= $card['image3'] ?>" alt="Изображение 3" class="thumbnail">
                    </div>
                </div>

                <div class="many_slider_right">
                    <img class="main-image" src="<?= $card['image'] ?>" alt="Основное изображение">
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Получаем все миниатюры и главное изображение
                const thumbnails = document.querySelectorAll('.thumbnail');
                const mainImage = document.querySelector('.main-image');

                // Добавляем обработчик клика для каждой миниатюры
                thumbnails.forEach(thumb => {
                    thumb.addEventListener('click', function () {
                        // Плавное исчезновение
                        mainImage.style.opacity = '0';

                        // Через 300мс меняем изображение и делаем его видимым
                        setTimeout(() => {
                            mainImage.src = this.src;
                            mainImage.style.opacity = '1';
                        }, 300);
                    });
                });
            });
        </script>

        <div class="odin_tovar_verh_right">
            <?php
            $stmt = $database->prepare('SELECT * FROM subcategories WHERE id = :id');
            $stmt->execute(['id' => $card['subcategory_id']]);
            $subcategory = $stmt->fetch();
            ?>
            <p class="category_odin"><?= $subcategory['title'] ?></p>

            <h6 class="nazv_odin"><?= $card['title'] ?></h6>

            <div class="cost_with_skidka_odin">
                <p class="cost_odin"><?= number_format($card['price'], 0, '', ' ') ?> ₽</p>
                <p class="skidka_odin"><?= number_format($card['price_skidka'], 0, '', ' ') ?> ₽</p>
            </div>
            <form action="" method="post">
                <input type="hidden" name="add_to_cart" value="1">
                <button class="button_pr">В корзину</button>
            </form>

            <div class="characteristics">
                <p class="characteristics_nazv">Подробные характеристики</p>

                <div class="characteristics_catalog">
                    <div class="spec-row">
                        <span class="spec-label">Артикул</span>
                        <span class="spec-dots"></span>
                        <span class="spec-value"><?= $card['id'] ?></span>
                    </div>

                    <div class="spec-row">
                        <span class="spec-label">Область применения</span>
                        <span class="spec-dots"></span>
                        <?php
                        $category = $database->query('SELECT * FROM categories WHERE id =' . $card['category_id'])->fetch();
                        ?>
                        <span class="spec-value"><?= $category['title'] ?></span>
                    </div>

                    <div class="spec-row">
                        <span class="spec-label">Тип продукта</span>
                        <span class="spec-dots"></span>
                        <?php
                        $stmt = $database->prepare('SELECT * FROM subcategories WHERE id = :id');
                        $stmt->execute(['id' => $card['subcategory_id']]);
                        $subcategory = $stmt->fetch();
                        ?>
                        <span class="spec-value"><?= $subcategory['title'] ?></span>
                    </div>

                    <div class="spec-row">
                        <span class="spec-label">Тип кожи</span>
                        <span class="spec-dots"></span>
                        <span class="spec-value"><?= $card['skin_type'] ?></span>
                    </div>

                    <div class="spec-row">
                        <span class="spec-label">Текстура</span>
                        <span class="spec-dots"></span>
                        <span class="spec-value"><?= $card['texture'] ?></span>
                    </div>

                    <div class="spec-row">
                        <span class="spec-label">Финиш</span>
                        <span class="spec-dots"></span>
                        <span class="spec-value"><?= $card['finish'] ?></span>
                    </div>

                    <div class="spec-row">
                        <span class="spec-label">Вес</span>
                        <span class="spec-dots"></span>
                        <span class="spec-value"><?= $card['weight'] ?> г</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="odin_tovar_niz">
        <div class="odin_tovar_niz_left">
            <p class="tovar_opisanie"><?= $card['description'] ?></p>
        </div>

        <div class="odin_tovar_niz_right">
            <div class="preim_card">
                <img src="assets/img/odin_tovar/prem1.svg" alt="">
                <p>Гарантия качества продукции</p>
            </div>
            <div class="preim_card">
                <img src="assets/img/odin_tovar/prem2.svg" alt="">
                <p>Доставка по всей России</p>
            </div>
            <div class="preim_card">
                <img src="assets/img/odin_tovar/prem3.svg" alt="">
                <p>Условия бесплатной доставки</p>
            </div>
        </div>
    </div>

    <div class="other_products_dalee">
        <div class="other_products_dalee">
            <h2>Вам также может понравиться</h2>

            <div class="katalog_other_products_dalee">
                <?php if (!empty($similar_products)): ?>
                    <?php foreach ($similar_products as $product): ?>
                        <a href="./?page=odin_tovar&id=<?= $product['id'] ?>">
                            <div class="osnov_card">
                                <div class="image_osn_card">
                                    <img class="image_tovar" src="<?= $product['image'] ?>" alt="Карточка товара">
                                    <!-- В блоке similar_products замените форму на эту версию: -->
                                    <form action="" method="post" class="add-to-cart-form">
                                        <input type="hidden" name="add_to_cart" value="1">
                                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                        <button type="submit" class="korzina_button <?= $in_cart ? 'in-cart' : '' ?>"></button>
                                    </form>
                                </div>
                                <div class="opisanie_osnov_card">
                                    <?php
                                    $stmt = $database->prepare('SELECT * FROM subcategories WHERE id = :id');
                                    $stmt->execute(['id' => $product['subcategory_id']]);
                                    $subcategory = $stmt->fetch();
                                    ?>
                                    <p class="category_osnov_card"><?= $subcategory['title'] ?></p>
                                    <p class="name_osnov_card"><?= $product['title'] ?></p>
                                    <div class="cost_with_skidka">
                                        <p class="cost"><?= number_format($product['price'], 0, '', ' ') ?> ₽</p>
                                        <p class="skidka"><?= number_format($product['price_skidka'], 0, '', ' ') ?> ₽</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Нет похожих товаров</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- odin_tovar end -->

<div class="odin_tovar_adaptiv container">
    <p class="category_odin">помада для губ матовая</p>

    <h6 class="nazv_odin">Anastasia Beverly Hills Matte Lipstick</h6>

    <img class="slider_tovars" src="assets/img/odin_tovar_adaptiv/slidre.svg" alt="">

    <div class="cost_with_skidka_odin">
        <p class="cost_odin">3 318 ₽</p>
        <p class="skidka_odin">3 687 ₽</p>
    </div>

    <button class="button_pr">В корзину</button>

    <p class="tovar_opisanie">Anastasia Beverly Hills Matte Lipstick - это пигментированная губная помада с
        бархатно-гладким ультра-матовым финишем и комфортной текстурой. Ультра пигментированная, стойкая
        губная помада невероятно ярких оттенков комфортно и мягко ложится на губы, делая их мягкими и
        насыщенными. Губная помада Matte Lipstick - это совершенство одним движением: блестящая пигментация
        и безумно гладкое нанесение. Она создана для любого вашего образа, будь то дневной или вечерний
        макияж, от естественного до гламурного.</p>

    <div class="characteristics">
        <p class="characteristics_nazv">Подробные характеристики</p>

        <div class="characteristics_catalog">
            <div class="spec-row">
                <span class="spec-label">Артикул</span>
                <span class="spec-dots"></span>
                <span class="spec-value">19000164735</span>
            </div>
            <div class="spec-row">
                <span class="spec-label">Область применения</span>
                <span class="spec-dots"></span>
                <span class="spec-value">губы</span>
            </div>
            <div class="spec-row">
                <span class="spec-label">Тип кожи</span>
                <span class="spec-dots"></span>
                <span class="spec-value">для всех типов кожи</span>
            </div>
            <div class="spec-row">
                <span class="spec-label">Текстура</span>
                <span class="spec-dots"></span>
                <span class="spec-value">кремовый</span>
            </div>
            <div class="spec-row">
                <span class="spec-label">Финиш</span>
                <span class="spec-dots"></span>
                <span class="spec-value">матовый</span>
            </div>
            <div class="spec-row">
                <span class="spec-label">Вес</span>
                <span class="spec-dots"></span>
                <span class="spec-value">3 г</span>
            </div>
        </div>
    </div>

    <div class="odin_tovar_niz_right">
        <div class="preim_card">
            <img src="assets/img/odin_tovar/prem1.svg" alt="">
            <p>Гарантия качества продукции</p>
        </div>
        <div class="preim_card">
            <img src="assets/img/odin_tovar/prem2.svg" alt="">
            <p>Доставка по всей России</p>
        </div>
        <div class="preim_card">
            <img src="assets/img/odin_tovar/prem3.svg" alt="">
            <p>Условия бесплатной доставки</p>
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

<!-- Скрипт для показа/скрытия уведомления -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Обработка всех форм добавления в корзину
        document.querySelectorAll('.add-to-cart-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault(); // Отменяем стандартную отправку формы

                const formData = new FormData(this);

                fetch('', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.text())
                    .then(data => {
                        // Показываем уведомление
                        showNotification();
                        setTimeout(hideNotification, 3000);

                        // Можно обновить кнопку, если нужно
                        const button = this.querySelector('.korzina_button');
                        button.classList.add('in-cart');
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            });
        });

        // Функции для уведомления (оставьте существующие)
        function showNotification() {
            const notification = document.getElementById('cart-notification');
            if (notification) {
                notification.classList.add('show');
            }
        }

        function hideNotification() {
            const notification = document.getElementById('cart-notification');
            if (notification) {
                notification.classList.add('fade-out');
                setTimeout(() => {
                    notification.classList.remove('show', 'fade-out');
                }, 500);
            }
        }
    });
</script>