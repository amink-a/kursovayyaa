<?php

if (!isset($_SESSION['user_id'])) {
    die('Войдите в аккаунт');
}

$all_subcategories = $database->query("SELECT * FROM subcategories")->fetchAll();

$user_id = $USER['id'];


$sql = "SELECT c.id AS cart_id, p.subcategory_id, p.title, p.price, p.price_skidka, p.image, c.count
        FROM carts c
        JOIN products p ON c.product_id = p.id
        WHERE c.user_id = $user_id";
$result = $database->query($sql)->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['increment']) {
    $cart_id = $_POST['cart_id'];
    $sql = "UPDATE carts SET count = count + 1 WHERE id = $cart_id";
    $database->query($sql);
    header('Location: ./?page=korzina');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['decrement']) {
    $cart_id = $_POST['cart_id'];
    $count = $_POST['count'];
    if ($count > 1) {
        $sql = "UPDATE carts SET count = count - 1 WHERE id = $cart_id";
        $database->query($sql);
        header('Location: ./?page=korzina');
    } else {
        $sql = "DELETE FROM carts WHERE id = $cart_id";
        $database->query($sql);
        header('Location: ./?page=korzina');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $cart_id = $_POST['cart_id'];
    $sql = "DELETE FROM carts WHERE id = $cart_id";
    $database->query($sql);
    header('Location: ./?page=korzina');
    exit();
}

$total_items = 0;
$total_price = 0;
$total_discount = 0;

foreach ($result as $item) {
    $total_items += $item['count'];
    if ($item['price_skidka'] > 0) {
        $total_price += $item['price_skidka'] * $item['count'];
    } else {
        $total_price += $item['price'] * $item['count'];
    }
}

?>

<!-- korzina start -->
<div class="korzina container">

    <h2>Корзина</h2>

    <div class="korzina_content container">
        <div class="korzina_left">
            <?php if (!empty($result)): ?>
                <?php foreach ($result as $cart): ?>
                    <div class="korzina_card">
                        <div class="img_nazv_kor">
                            <img src="<?= $cart['image'] ?>" alt="">
                            <div class="dobavleny_tovar">
                                <?php
                                $stmt = $database->prepare('SELECT * FROM subcategories WHERE id = :id');
                                $stmt->execute(['id' => $cart['subcategory_id']]);
                                $subcategory = $stmt->fetch();
                                ?>
                                <p class="dobavleny_tovar_category"><?= $subcategory['title'] ?></p>
                                <p class="dobavleny_tovar_nazv"><?= $cart['title'] ?></p>
                            </div>
                        </div>
                        <div class="cost_with_cound">
                            <div class="costs_skidka">
                                <p class="cost_kor"><?= number_format($cart['price'], 0, '', ' ') ?> ₽</p>
                                <p class="skidka_kor"><?= number_format($cart['price_skidka'], 0, '', ' ') ?> ₽</p>
                            </div>

                            <div class="dobavit_udalit">
                                <div class="plus_minus">
                                    <form action="" method="post">
                                        <input type="hidden" name="count" value="<?= $cart['count'] ?>">
                                        <input type="hidden" name="cart_id" value="<?= $cart['cart_id'] ?>">
                                        <input class="pm" type="submit" value="-" name="decrement">
                                    </form>
                                    <p class="shetchik"><?= $cart['count'] ?></p>
                                    <form action="" method="post">
                                        <input type="hidden" name="cart_id" value="<?= $cart['cart_id'] ?>">
                                        <input class="pm" type="submit" value="+" name="increment">
                                    </form>
                                </div>

                                <form action="" method="post">
                                    <input type="hidden" name="cart_id" value="<?= $cart['cart_id'] ?>">
                                    <button class="udalit" type="submit" name="delete"
                                        onclick="return confirm('Вы точно желаете удалить данный товар?')">×</button>
                                </form>

                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Корзина пуста</p>
            <?php endif; ?>
        </div>


        <div class="korzina_right">
            <button class="btn_pink" type="submit">Оформить</button>

            <p class="itog_summa">Сумма заказа</p>

            <div class="sum">
                <div class="spec-row">
                    <span class="spec-label"><?= $total_items ?> товар(ов)</span>
                    <span class="spec-dots"></span>
                    <span class="spec-value"><?= number_format($total_price, 0, '', ' ') ?> ₽</span>
                </div>
            </div>

            <div class="sum_itog">
                <div class="spec-row">
                    <span class="spec-label">Итого</span>
                    <span class="spec-dots"></span>
                    <span class="spec-value"><?= number_format($total_price, 0, '', ' ') ?> ₽</span>
                </div>
            </div>

            <button class="btn_pink_adaptiv" type="submit">Оформить</button>
        </div>
    </div>
</div>


<!-- korzina end -->