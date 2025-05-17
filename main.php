<?php

$random_products_ids = [10, 11, 12, 13]; // ID для новинок (4 товара)
$popular_products_ids = [5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16]; // ID для популярных товаров (12 товаров)

// Запрос для новинок
$random_products = $database->query("SELECT * FROM products WHERE id IN (" . implode(',', $random_products_ids) . ")")->fetchAll();

// Запрос для популярных товаров
$popular_products = $database->query("SELECT * FROM products WHERE id IN (" . implode(',', $popular_products_ids) . ")")->fetchAll();

$categories = $database->query("SELECT * FROM categories")->fetchAll();

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
        header("Location: " . $_SERVER['PHP_SELF']);
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
                header("Location: ./");
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

?>

<?php session_start(); ?>
<?php if (isset($_SESSION['user_id'])): ?>
    <p>Вы зашли в аккаунт</p>
<?php endif; ?>


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

<!-- banner start -->
<div class="banner container">
    <div class="banner_left">
        <img src="assets/img/banner/b1.svg" alt="">
    </div>

    <div class="banner_right">
        <div class="foto">
            <img src="assets/img/banner/b2.svg" alt="">
            <img src="assets/img/banner/b3.svg" alt="">
        </div>

        <h1>Покоряй мир своей красотой – начни прямо сейчас!</h1>

        <div class="katalog_button">
            <a href="./?page=katalog"><button type="submit">В каталог →</button></a>
            <p>Погрузись в мир сияющей кожи и ухоженных волос с нашим шикарным ассортиментом косметики!</p>
        </div>
    </div>
</div>

<div class="banner_adaptiv container">
    <h1>Покоряй мир своей красотой – начни прямо сейчас!</h1>

    <div class="foto">
        <img src="assets/img/banner/b2.svg" alt="">
        <img src="assets/img/banner/b3.svg" alt="">
    </div>

    <div class="katalog_button">
        <button type="submit">В каталог →</button>
        <p>Погрузись в мир сияющей кожи и ухоженных волос с нашим шикарным ассортиментом косметики!</p>
    </div>
</div>
<!-- banner end -->

<!-- infoblok start -->
<div class="infoblok container my-120">
    <div class="title">
        <h2>Мы предлагаем</h2>
    </div>

    <div class="infoblock_info">

        <div class="preimuchestva_stolbec">
            <div class="preimuchestva_card">
                <div class="left">
                    <img class="odin" src="assets/img/infoblok/1.svg" alt="">
                </div>
                <div class="right">
                    <h3>Широкий ассортимент</h3>
                    <p>В нашем магазине представлена разнообразная косметика и средства по уходу за кожей и волосами
                        от
                        популярных брендов, что позволяет каждому найти продукты, подходящие именно
                        ему.
                    </p>
                </div>
            </div>

            <div class="preimuchestva_card">
                <div class="left">
                    <img src="assets/img/infoblok/2.svg" alt="">
                </div>
                <div class="right">
                    <h3>Безопасность и качество</h3>
                    <p>Мы тщательно отбираем только сертифицированные и качественные продукты, чтобы гарантировать
                        безопасность нашей косметики.
                    </p>
                </div>
            </div>
        </div>

        <img class="pomada" src="assets/img/infoblok/pomada.svg" alt="">

        <div class="preimuchestva_stolbec">
            <div class="preimuchestva_card">
                <div class="left">
                    <img src="assets/img/infoblok/3.svg" alt="">
                </div>
                <div class="right">
                    <h3>Доступные цены</h3>
                    <p>Мы предлагаем конкурентоспособные цены и регулярные акции, благодаря чему покупка
                        качественной косметики становится доступной для каждого.
                    </p>
                </div>
            </div>

            <div class="preimuchestva_card">
                <div class="left">
                    <img src="assets/img/infoblok/4.svg" alt="">
                </div>
                <div class="right">
                    <h3>Удобный интерфейс</h3>
                    <p>Наш сайт прост в навигации и предоставляет удобные фильтры для поиска товаров, что позволяет
                        быстро найти интересующие продукты.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- infoblok end -->

<!-- popular_kategoty start -->
<div class="popular_kategoty container my-120">
    <div class="title">
        <h2>Популярные категории</h2>
    </div>

    <div class="popular_kategoty_katalog">

        <div class="popular_kategoty_left">
            <div class="category">
                <img src="assets/img/popular_kategory/c1.svg" alt="">
                <a href="./?page=katalog&category=1"><button type="submit">Губы →</button></a>
            </div>
        </div>

        <div class="popular_kategoty_right">
            <div class="category">
                <img class="image_cat" src="assets/img/popular_kategory/c2.svg" alt="">
                <a href="./?page=katalog&category=4"><button type="submit">Лицо →</button></a>
            </div>
            <div class="category">
                <img class="category_more" src="assets/img/popular_kategory/c3.svg" alt="">
                <a href="./?page=katalog&category=2"><button type="submit">Брови →</button></a>
            </div>
            <div class="category">
                <img src="assets/img/popular_kategory/c4.svg" alt="">
                <a href="./?page=katalog&category=2"><button type="submit">Глаза →</button></a>
            </div>
            <div class="category">
                <img class="category_more" src="assets/img/popular_kategory/c5.svg" alt="">
                <a href="./?page=katalog&category=5"><button type="submit">Аксуссуары →</button></a>
            </div>
        </div>
    </div>
</div>

<div class="popular_kategoty_2 container my-120">
    <div class="title">
        <h2>Популярные категории</h2>
    </div>

    <div class="popular_kategoty_katalog_2">
        <div class="popular_kategoty_verh">
            <div class="category">
                <img src="assets/img/popular_kategory/accessories_2.svg" alt="">
                <button type="submit">Аксуссуары →</button>
            </div>
        </div>

        <div class="popular_kategoty_niz">
            <div class="category">
                <img src="assets/img/popular_kategory/lips_2.svg" alt="">
                <button type="submit">Губы →</button>
            </div>
            <div class="category">
                <img class="category_more" src="assets/img/popular_kategory/face_2.svg" alt="">
                <button type="submit">Лицо →</button>
            </div>
            <div class="category">
                <img src="assets/img/popular_kategory/brows_2.svg" alt="">
                <button type="submit">Брови →</button>
            </div>
            <div class="category">
                <img class="category_more" src="assets/img/popular_kategory/eyes_2.svg" alt="">
                <button type="submit">Глаза →</button>
            </div>
        </div>
    </div>
</div>
<!-- popular_kategoty end -->

<div class="popular_kategoty_2 container my-120">
    <div class="title">
        <h2>Популярные категории</h2>
    </div>

    <div class="popular_kategoty_katalog_2">
        <div class="popular_kategoty_verh">
            <div class="category">
                <img src="assets/img/popular_kategory/accessories_2.svg" alt="">
                <button type="submit">Аксуссуары →</button>
            </div>
        </div>

        <div class="popular_kategoty_niz">
            <div class="category">
                <img src="assets/img/popular_kategory/lips_2.svg" alt="">
                <button type="submit">Губы →</button>
            </div>
            <div class="category">
                <img class="category_more" src="assets/img/popular_kategory/face_2.svg" alt="">
                <button type="submit">Лицо →</button>
            </div>
            <div class="category">
                <img src="assets/img/popular_kategory/brows_2.svg" alt="">
                <button type="submit">Брови →</button>
            </div>
            <div class="category">
                <img class="category_more" src="assets/img/popular_kategory/eyes_2.svg" alt="">
                <button type="submit">Глаза →</button>
            </div>
        </div>
    </div>
</div>
<!-- popular_kategoty end -->

<!-- novinki start -->
<div class="novinki container my-120">
    <div class="title">
        <h2>Новинки: отслеживаем актуальные тренды</h2>
    </div>

    <div class="novinki_content">
        <div class="novinki_left">
            <img src="assets/img/novinki/novinki.svg" alt="">
            <div class="collection_text">
                <p>#Коллекция 2025</p>
                <p>Осенняя коллекция</p>
            </div>
        </div>

        <div class="novinki_right">
            <?php foreach ($random_products as $product): ?>
                <a href="./?page=odin_tovar&id=<?= $product['id'] ?>">
                    <div class="osnov_card">
                        <div class="image_osn_card">
                            <img class="image_tovar" src="<?= $product['image'] ?>" alt="Карточка товара">
                            <form action="" method="post">
                                <input type="hidden" name="add_to_cart" value="1">
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                <button type="submit" class="korzina_button">
                                </button>
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
        </div>
    </div>
</div>
</div>

<div class="novinki_adaptive container my-120">
    <div class="title">
        <h2>Новинки: отслеживаем <br> актуальные тренды</h2>
    </div>

    <div class="novinki_content">
        <div class="novinki_verh">
            <img src="assets/img/novinki/novinki_adaptive.png" alt="">
            <div class="collection_text">
                <p>#Коллекция 2025</p>
                <p>Осенняя коллекция</p>
            </div>
        </div>
    </div>

    <div class="novinki_niz">
        <div class="osnov_card">
            <div class="image_osn_card">
                <img class="image_tovar" src="assets/img/osnov_card/tovar.svg" alt="">
                <img class="korzina_button" src="assets/img/osnov_card/korzina.svg" alt="">
            </div>

            <div class="opisanie_osnov_card">
                <p class="category_osnov_card">помада для губ матовая</p>
                <p class="name_osnov_card">Anastasia Beverly Hills Matte Lipstick</p>
                <div class="cost_with_skidka">
                    <p class="cost">3 318 ₽</p>
                    <p class="skidka">3 687 ₽</p>
                </div>
            </div>
        </div>

        <div class="osnov_card">
            <div class="image_osn_card">
                <img class="image_tovar" src="assets/img/osnov_card/tovar.svg" alt="">
                <img class="korzina_button" src="assets/img/osnov_card/korzina.svg" alt="">
            </div>

            <div class="opisanie_osnov_card">
                <p class="category_osnov_card">помада для губ матовая</p>
                <p class="name_osnov_card">Anastasia Beverly Hills Matte Lipstick</p>
                <div class="cost_with_skidka">
                    <p class="cost">3 318 ₽</p>
                    <p class="skidka">3 687 ₽</p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- novinki end -->

<!-- personal start -->
<div class="personal my-120">
    <div class="personal_content container">

        <h2>Персональные предложения</h2>

        <P>Промокоды, скидки и акции, подобранные специально для Вас</P>

        <div class="rassylka">
            <input type="email" placeholder="E-mail">
            <div class="chekbox">
                <input type="checkbox" name="" id="">
                <label for="">Я согласен на обработку данных в соответствии с ФЗ РФ от 27.07.2006, №152 ФЗ "О
                    персональных данных"</label>
            </div>
        </div>

        <button class="btn" type="submit">Подписаться</button>
    </div>
</div>
<!-- personal end -->

<!-- popular start -->
<div class="popular container my-120">
    <div class="title">
        <h2>Популярные товары</h2>
    </div>

    <div class="popular_content">
        <div class="popukar_katalog">
            <?php foreach ($popular_products as $product): ?>
                <a href="./?page=odin_tovar&id=<?= $product['id'] ?>">
                    <div class="osnov_card">
                        <div class="image_osn_card">
                            <img class="image_tovar" src="<?= $product['image'] ?>" alt="Карточка товара">
                            <form action="" method="post">
                                <input type="hidden" name="add_to_cart" value="1">
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                <button type="submit" class="korzina_button">
                                </button>
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
        </div>

        <div class="slider">
            <button type="button" class="arrowLeftBtnSlider">
                <img src="assets/img/popular/str1.svg" alt="Предыдущий">
            </button>
            <button type="button" class="arrowRightBtnSlider">
                <img src="assets/img/popular/str2.svg" alt="Следующий">
            </button>
        </div>
    </div>
</div>

</body>

</html>

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