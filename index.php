<?php

include('elements/connection.php');
include('elements/head.php');


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Сияние</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="shortcut icon" href="assets/img/favicon/logo.png" type="image/x-icon">
    <script src="assets/js/main.js" defer></script>
</head>

<body>

    <?php
    include('elements/header.php');

    if (isset($_GET['page'])) {
        $page = $_GET['page'];

        if ($page === 'register') {
            include('register.php');
        } elseif ($page === 'login') {
            include('login.php');
        } elseif ($page === 'katalog') {
            include('katalog.php');
        } elseif ($page === 'odin_tovar') {
            include('odin_tovar.php');
        } elseif ($page === 'profil') {
            include('profil.php');
        } elseif ($page === 'edit') {
            include('edit.php');
        } elseif ($page === 'create') {
            include('create.php');
        } elseif ($page === 'admin_category') {
            include('admin_category.php');
        } elseif ($page === 'admin_person') {
            include('admin_person.php');
        } elseif ($page === 'admin_tovar') {
            include('admin_tovar.php');
        } elseif ($page === 'admin_zakaz') {
            include('admin_zakaz.php');
        } elseif ($page === 'katalog_cards_phone_all') {
            include('katalog_cards_phone_all.php');
        } elseif ($page === 'katalog_telephone') {
            include('katalog_telephone.php');
        } elseif ($page === 'korzina_not') {
            include('korzina_not.php');
        } elseif ($page === 'korzina') {
            include('korzina.php');
        } elseif ($page === 'zakaz') {
            include('zakaz.php');
        } elseif ($page === 'admin_subcategories') {
            include('admin_subcategories.php');
        } elseif ($page === 'add_admin_subcategories') {
            include('add_admin_subcategories.php');
        } elseif ($page === 'edit_admin_subcategories') {
            include('edit_admin_subcategories.php');
        }
    } else {
        include('main.php');
    }

    include('elements/footer.php');
    ?>


</body>

</html>