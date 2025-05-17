<?php

$categories = $database->query('SELECT * FROM categories')->fetchAll();
$subcategories = $database->query('SELECT * FROM subcategories')->fetchAll();

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
        function uploadImage($file, $defaultImage = 'assets/img/placeholder.jpg')
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
            return $defaultImage; // Возвращаем заглушку, если загрузка не удалась
        }

        // Обрабатываем все изображения
        $mainImage = uploadImage($_FILES['image'] ?? null);
        $image2 = uploadImage($_FILES['image2'] ?? null);
        $image3 = uploadImage($_FILES['image3'] ?? null);

        // Вставляем данные в БД
        try {
            $stmt = $database->prepare("INSERT INTO products(
                title, price, price_skidka, skin_type, 
                texture, finish, weight, description, category_id, subcategory_id,
                image, image2, image3
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

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
                $image3
            ]);

            header('Location: ./');
            exit;
        } catch (PDOException $e) {
            echo 'Ошибка при сохранении в базу данных: ' . $e->getMessage();
        }
    }
}
?>

<!-- create start -->
<div class="edit container">
    <div class="title">
        <h2>Добавление товара</h2>
    </div>

    <div class="forma_osnova">
        <form action="" method="post" enctype="multipart/form-data">

            <div class="img_ad">
                <label for="image">Изображение основного товара:</label>
                <input type="file" name="image" id="image" required>

                <label for="image">Доболнительное изоброжение 1:</label>
                <input type="file" name="image2" id="image" required>

                <label for="image">Доболнительное изоброжение 2:</label>
                <input type="file" name="image3" id="image" required>
            </div>


            <label for="id">Артикул</label>
            <input name="id" type="text" placeholder="Артикул">

            <label for="">Область применения</label>
            <select name="category_id" id="">
                <option value="">Область применения</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['id'] ?>"><?= $category['title'] ?></option>
                <?php endforeach; ?>
            </select>

            <label for="">Тип продукта</label>
            <select name="subcategory_id" id="">
                <option value="">Тип продукта</option>
                <?php foreach ($subcategories as $subcat): ?>
                    <option value="<?= $subcat['id'] ?>"><?= $subcat['title'] ?></option>
                <?php endforeach; ?>
            </select>

            <label for="title">Название</label>
            <input name="title" type="text" placeholder="Название" required>

            <label for="price">Цена</label>
            <input name="price" type="number" step="0.01" placeholder="Цена" required>

            <label for="price_skidka">Цена со скидкой</label>
            <input name="price_skidka" type="number" step="0.01" placeholder="Цена со скидкой" required>

            <label for="skin_type">Тип кожи</label>
            <input name="skin_type" type="text" placeholder="Тип кожи" required>

            <label for="texture">Текстура</label>
            <input name="texture" type="text" placeholder="Текстура" required>

            <label for="finish">Финиш</label>
            <input name="finish" type="text" placeholder="Финиш" required>

            <label for="weight">Вес</label>
            <input name="weight" type="number" step="0.01" placeholder="Вес" required>

            <label for="description">Описание</label>
            <textarea name="description" placeholder="Описание" required></textarea>

            <button class="btn_pink" type="submit">Добавить товар</button>

        </form>
    </div>
</div>
<!-- create end -->