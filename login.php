<?php

if (isset($_SESSION['user_id'])) {
    header('Location: ./');
}

$flag = true;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
}

?>

<!-- login start -->
<div class="register container">
    <div class="register_forma">
        <h2>Авторизация</h2>

        <form action="" method="post">
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
                }
            }
            ?>

            <input name="password" type="password" name="" id="" placeholder="Пароль">
            <?php
            if (isset($_POST['password'])) {
                if (empty($password)) {
                    echo 'Заполните пароль';
                    $flag = false;
                } else {
                    $sql = "SELECT * FROM users WHERE email = :email";
                    $stmt = $database->prepare($sql);
                    $stmt->execute(['email' => $email]);
                    $result = $stmt->fetch();
                    
                    if (!$result) {
                        $flag = false;
                        echo "Пользователя не существует";
                    } else {
                        if ($result['is_blocked']) {
                            $flag = false;
                            echo '<div class="blocked-message">Ваш аккаунт заблокирован</div>';
                        } else if (password_verify($password, $result['password'])) {
                            session_start();
                            $_SESSION['user_id'] = $result['id'];
                            header('Location: ./');
                        } else {
                            $flag = false;
                            echo 'Пароль не верен';
                        }
                    }
                }
            }
            ?>

            <button class="btn_pink">Авторизация</button>

        </form>
    </div>
    <div class="vopros">
        <a href="#">Нет профиля?</a>
        <a href="register.html"><button class="button_pr">Создать</button></a>
    </div>
</div>
<!-- login end -->

<style>
.blocked-message {
    background-color: #ffebee;
    color: #c62828;
    padding: 10px;
    border-radius: 4px;
    margin: 10px 0;
    text-align: center;
    font-weight: bold;
}
</style>