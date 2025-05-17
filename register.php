<?php

if (isset($_SESSION['user_id'])) {
    header('Location: ./');
}

$flag = true;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];
}

?>

<!-- register start -->
<div class="register container">
    <div class="regoster_forma">
        <div class="title">
            <h2>Регистрация</h2>
        </div>

        <form action="" method="post">
            <input name="name" type="text" placeholder="Имя" value="<?= isset($_POST['name']) ? $_POST['name'] : '' ?>">
            <?php
            if (isset($_POST['name'])) {
                if (empty($name)) {
                    $flag = false;
                    echo 'Заполните имя';
                }
            }
            ?>

            <input name="surname" type="text" placeholder="Фамилия"
                value="<?= isset($_POST['surname']) ? $_POST['surname'] : '' ?>">
            <?php
            if (isset($_POST['surname'])) {
                if (empty($surname)) {
                    echo 'Заполните фамилию';
                    $flag = false;
                }
            }
            ?>

            <input name="email" type="email" name="" id="" placeholder="E-mail"
                value="<?= isset($_POST['email']) ? $_POST['email'] : '' ?>">
            <?php
            if (isset($_POST['email'])) {
                if (empty($email)) {
                    echo 'Заполните email';
                    $flag = false;
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $flag = false;
                    echo 'Email не валиден';
                } else {
                    $sql = "SELECT * FROM users WHERE email = '$email'";
                    $stmt = $database->query($sql);
                    $result = $stmt->fetch();

                    if ($result) {
                        echo 'пользователь существует';
                        $flag = false;
                    }
                }
            }
            ?>

            <input name="password" type="password" name="" id="" placeholder="Пароль">
            <?php
            if (isset($_POST['password'])) {
                if (empty($password)) {
                    echo 'Заполните пароль';
                    $flag = false;
                } elseif (strlen($password) < 6) {
                    $flag = false;
                    echo 'Введите более 6 символов';
                }
            }
            ?>

            <input name="password_confirm" type="password" name="" id="" placeholder="Повторите пароль">
            <?php
            if (isset($_POST['password_confirm'])) {
                if (empty($password_confirm)) {
                    echo 'Заполните повторный пароль';
                    $flag = false;
                } elseif ($password != $password_confirm) {
                    $flag = false;
                    echo 'Пароли не совпадают';
                }
            }
            ?>

            <div class="chekbox">
                <input type="checkbox" name="" id="">
                <label for="">Я согласен на обработку данных в соответствии с ФЗ РФ от 27.07.2006, №152 ФЗ “О
                    персональных данных”</label>
            </div>
            <button class="btn_pink">Регистрация</button>
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if ($flag) {
                    $password = password_hash($password, PASSWORD_DEFAULT);
                    $sql = "INSERT INTO users (name, surname, email, password) VALUES ('$name', '$surname', '$email', '$password')";
                    $database->query($sql);
                    // echo "<script>window.location.href = './'</script>";
                    header("Location: ./?page=login");
                }
            }
            ?>
        </form>
    </div>
    <div class="vopros">
        <a href="#">Уже есть аккаунт?</a>
        <a href="./?page=login"><button class="button_pr">Войти</button></a>
    </div>
</div>
<!-- register end -->