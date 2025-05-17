<?php

$categories = $database->query('SELECT * FROM categories')->fetchAll();
$subcategories = $database->query('SELECT * FROM subcategories')->fetchAll();

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $database->query("SELECT * FROM products WHERE id = $id");
    $card = $stmt->fetch();

    if (!$card) {
        echo 'Товар не найден';
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === "POST") {
        // Получаем данные из формы
        $title = $_POST['title'];
        $price = $_POST['price'];
        $price_skidka = $_POST['price_skidka'];
        $skin_type = $_POST['skin_type'];
        $texture = $_POST['texture'];
        $finish = $_POST['finish'];
        $weight = $_POST['weight'];
        $description = $_POST['description'];
        $category_id = $_POST['category_id'];
        $subcategory_id = $_POST['subcategory_id'];

        // Проверка обязательных полей
        if (
            empty($title) || empty($price) || empty($price_skidka) ||
            empty($skin_type) || empty($texture) || empty($finish) || empty($weight) ||
            empty($description)
        ) {
            echo 'Пустые поля';
        }
        // Проверка числовых полей
        elseif (!is_numeric($price) || !is_numeric($price_skidka) || !is_numeric($weight)) {
            echo 'Введите число';
        } else {
            // Функция для обработки загрузки изображения
            function uploadImage($file, $currentImage)
            {
                if (isset($file) && $file['error'] === UPLOAD_ERR_OK) {
                    $tmpName = $file['tmp_name'];
                    $name = basename($file['name']);
                    $extension = pathinfo($name, PATHINFO_EXTENSION);
                    $newName = uniqid() . '.' . $extension;
                    $newDirection = 'uploads/' . $newName;

                    if (move_uploaded_file($tmpName, $newDirection)) {
                        return $newDirection;
                    }
                }
                return $currentImage; // Возвращаем текущее изображение, если новое не загружено
            }

            // Обрабатываем все изображения, передавая текущие значения
            $mainImage = uploadImage($_FILES['image'] ?? null, $card['image']);
            $image2 = uploadImage($_FILES['image2'] ?? null, $card['image2']);
            $image3 = uploadImage($_FILES['image3'] ?? null, $card['image3']);

            // Обновляем данные в БД
            try {
                $stmt = $database->prepare("UPDATE products SET
                    title = ?,
                    price = ?,
                    price_skidka = ?,
                    skin_type = ?,
                    texture = ?,
                    finish = ?,
                    weight = ?,
                    description = ?,
                    category_id = ?,
                    subcategory_id = ?,
                    image = ?,
                    image2 = ?,
                    image3 = ?
                WHERE id = ?");

                $stmt->execute([
                    $title,
                    $price,
                    $price_skidka,
                    $skin_type,
                    $texture,
                    $finish,
                    $weight,
                    $description,
                    $category_id,
                    $subcategory_id,
                    $mainImage,
                    $image2,
                    $image3,
                    $id
                ]);

                header('Location: ./');
                exit;
            } catch (PDOException $e) {
                echo 'Ошибка при сохранении в базу данных: ' . $e->getMessage();
            }
        }
    }
}
?>

<!-- edit start -->
<div class="edit container">
    <div class="title">
        <h2>Редактирование товара</h2>
    </div>

    <div class="forma_osnova">
        <form action="" method="post" enctype="multipart/form-data">

            <div class="img_ad">
                <label for="image">Изображение основного товара:</label>
                <input type="file" name="image" id="image">
                <?php if (!empty($card['image'])): ?>
                    <div class="current-image">
                        <p>Текущее изображение:</p>
                        <img src="<?= $card['image'] ?>" alt="Current image" style="max-width: 200px;">
                    </div>
                <?php endif; ?>

                <label for="image2">Дополнительное изображение 1:</label>
                <input type="file" name="image2" id="image2">
                <?php if (!empty($card['image2'])): ?>
                    <div class="current-image">
                        <p>Текущее изображение:</p>
                        <img src="<?= $card['image2'] ?>" alt="Current image 2" style="max-width: 200px;">
                    </div>
                <?php endif; ?>

                <label for="image3">Дополнительное изображение 2:</label>
                <input type="file" name="image3" id="image3">
                <?php if (!empty($card['image3'])): ?>
                    <div class="current-image">
                        <p>Текущее изображение:</p>
                        <img src="<?= $card['image3'] ?>" alt="Current image 3" style="max-width: 200px;">
                    </div>
                <?php endif; ?>
            </div>

            <label for="id">Артикул</label>
            <input name="id" type="text" placeholder="Артикул" value="<?= $card['id'] ?>" readonly>

            <label for="category_id">Область применения</label>
            <select name="category_id" id="category_id">
                <option value="">Область применения</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['id'] ?>" <?= $category['id'] == $card['category_id'] ? 'selected' : '' ?>>
                        <?= $category['title'] ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="subcategory_id">Тип продукта</label>
            <select name="subcategory_id" id="subcategory_id">
                <option value="">Тип продукта</option>
                <?php foreach ($subcategories as $subcat): ?>
                    <option value="<?= $subcat['id'] ?>" <?= $subcat['id'] == $card['subcategory_id'] ? 'selected' : '' ?>>
                        <?= $subcat['title'] ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <!-- Остальные поля формы остаются без изменений -->
            <label for="title">Название</label>
            <input name="title" type="text" placeholder="Название" required value="<?= $card['title'] ?>">

            <label for="price">Цена</label>
            <input name="price" type="number" step="0.01" placeholder="Цена" required value="<?= $card['price'] ?>">

            <label for="price_skidka">Цена со скидкой</label>
            <input name="price_skidka" type="number" step="0.01" placeholder="Цена со скидкой" required
                value="<?= $card['price_skidka'] ?>">

            <label for="skin_type">Тип кожи</label>
            <input name="skin_type" type="text" placeholder="Тип кожи" required value="<?= $card['skin_type'] ?>">

            <label for="texture">Текстура</label>
            <input name="texture" type="text" placeholder="Текстура" required value="<?= $card['texture'] ?>">

            <label for="finish">Финиш</label>
            <input name="finish" type="text" placeholder="Финиш" required value="<?= $card['finish'] ?>">

            <label for="weight">Вес</label>
            <input name="weight" type="number" step="0.01" placeholder="Вес" required value="<?= $card['weight'] ?>">

            <label for="description">Описание</label>
            <textarea name="description" placeholder="Описание" required><?= $card['description'] ?></textarea>

            <button class="btn_pink" type="submit">Сохранить изменения</button>

        </form>
    </div>
</div>
<!-- edit end -->