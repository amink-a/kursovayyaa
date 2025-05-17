<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Получаем данные пользователя
$stmt = $database->query("SELECT * FROM users WHERE id = $user_id");
$user = $stmt->fetch();

if (!$user) {
    die('Пользователь не найден');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $surname = $_POST['surname'] ?? '';
    $patronymic = $_POST['patronymic'] ?? '';
    $email = $_POST['email'] ?? '';
    $telephone = $_POST['telephone'] ?? '';
    $gender = $_POST['gender'] ?? '';
    var_dump($gender);

    // Валидация
    $errors = [];
    if (empty($name))
        $errors[] = 'Имя обязательно';
    if (empty($surname))
        $errors[] = 'Фамилия обязательна';
    if (empty($email))
        $errors[] = 'Email обязателен';

    if (empty($errors)) {
        $sql = "UPDATE users SET
                name = :name,
                surname = :surname,
                patronymic = :patronymic,
                email = :email,
                telephone = :telephone,
                gender = :gender
                WHERE id = :id";

        $stmt = $database->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':surname', $surname);
        $stmt->bindParam(':patronymic', $patronymic);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':telephone', $telephone);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':id', $user_id);

        if ($stmt->execute()) {
            // Обновляем данные в переменной $user
            $stmt = $database->query("SELECT * FROM users WHERE id = $user_id");
            $user = $stmt->fetch();
        } else {
            $errors[] = 'Ошибка при обновлении данных';
        }
    }
}
?>

<!-- profil start -->
<div class="profil container">
    <div class="profil_left">
        <div class="person">
            <p><?= htmlspecialchars($user['surname'] . ' ' . $user['name']) ?></p>
        </div>

        <div class="fyltr_profil">
            <a class="perekluchat" href="./?page=profil">Профиль</a>
            <a class="perekluchat" href="./?page=zakaz">Заказы</a>
        </div>
    </div>

    <div class="profil_right">
        <h2>Мой профиль</h2>

        <!-- Вывод сообщений об ошибках/успехе -->
        <?php if (!empty($errors)): ?>
            <div class="errors">
                <?php foreach ($errors as $error): ?>
                    <p><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    

        <div class="profil_right_content">
            <form action="" method="post">
                <div class="forms_profil">
                    <h6>личная информация</h6>
                    <input type="text" name="name" class="profile-input" placeholder="Имя" value="<?= htmlspecialchars($user['name']) ?>" required>
                    <input type="text" name="surname" class="profile-input" placeholder="Фамилия" value="<?= htmlspecialchars($user['surname']) ?>" required>
                    <input type="text" name="patronymic" class="profile-input" placeholder="Отчество" value="<?= htmlspecialchars($user['patronymic'] ?? '') ?>">

                    <div class="chekboxx">
                        <div class="chek">
                            <input type="radio" name="gender" id="male" value="man"  <?= $user['gender'] == 'man' ? 'checked' : '' ?>>
                            <label for="male">Мужской</label>
                        </div>

                        <div class="chek">
                            <input type="radio" name="gender" id="female" value="woman" <?= $user['gender'] == 'woman' ? 'checked' : '' ?>>
                            <label for="female">Женский</label>
                        </div>
                    </div>
                </div>

                <div class="forms_profil">
                    <h6>контакты</h6>
                    <input type="email" name="email" class="profile-input" placeholder="E-mail" value="<?= htmlspecialchars($user['email']) ?>" required>
                    <input type="tel" name="telephone" class="profile-input" placeholder="Телефон" value="<?= htmlspecialchars($user['telephone'] ?? '') ?>">
                </div>

                <button class="btn_pink" type="submit">Сохранить изменения</button>
            </form>
        </div>
    </div>
</div>
<!-- profil end -->

<style>
.profile-input {
    width: 100%;
    padding: 12px 15px;
    margin-bottom: 15px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    transition: border-color 0.3s ease;
}

.profile-input:focus {
    outline: none;
    border-color: #ff69b4;
    box-shadow: 0 0 5px rgba(255, 105, 180, 0.2);
}

.profile-input::placeholder {
    color: #999;
}

.forms_profil {
    margin-bottom: 30px;
}

.chekboxx {
    display: flex;
    gap: 20px;
    margin-top: 10px;
}

.chek {
    display: flex;
    align-items: center;
    gap: 5px;
}

.chek input[type="radio"] {
    margin: 0;
}

.chek label {
    font-size: 14px;
    color: #333;
}

.btn_pink {
    background-color: #ff69b4;
    color: white;
    padding: 12px 25px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    transition: background-color 0.3s ease;
}

.btn_pink:hover {
    background-color: #ff1493;
}

.errors {
    background-color: #fff3f3;
    border: 1px solid #ffcdd2;
    border-radius: 4px;
    padding: 10px 15px;
    margin-bottom: 20px;
}

.errors p {
    color: #d32f2f;
    margin: 5px 0;
    font-size: 14px;
}
</style>