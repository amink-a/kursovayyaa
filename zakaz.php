<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Сияние</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="shortcut icon" href="assets/img/favicon/logo.png" type="image/x-icon">
    <script src="assets/js/main.js"></script>

<body>
    <!-- header start -->
    <header class="container">

        <nav class="burger_desctop">
            <a href="katalog.html">Каталог</a>
            <a href="#">Коллекции</a>
            <a href="#">Новинки</a>
            <a href="#">Категории</a>
        </nav>

        <div class="burger_adaptiv">
            <input type="checkbox" id="burger">
            <label for="burger">
                <span></span>
            </label>
            <nav>
                <a href="katalog.html">Каталог</a>
                <a href="#">Коллекции</a>
                <a href="#">Новинки</a>
                <a href="#">Категории</a>
            </nav>
            <img class="luupa" src="assets/img/header/lupa.svg" alt="">
        </div>

        <div class="burger_adaptiv_telephone">
            <input type="checkbox" id="burger2">
            <label for="burger2">
                <span></span>
            </label>
            <nav>
                <a href="katalog_telephone.html">Каталог</a>
                <a href="#">Коллекции</a>
                <a href="#">Новинки</a>
                <a href="#">Категории</a>
            </nav>
            <img class="luupa" src="assets/img/header/lupa.svg" alt="">
        </div>

        <div class="logo">
            <a href="#"><img src="assets/img/header/logo.svg" alt=""></a>
        </div>

        <nav class="burger_desctop">
            <div class="poisk">
                <img src="assets/img/header/lupa.svg" alt="">
                <input type="text" placeholder="Поиск...">
            </div>
            <img src="assets/img/header/korzina.svg" alt="">
            <img src="assets/img/header/person.svg" alt="">
        </nav>

        <div class="burger_adaptiv">
            <img src="assets/img/header/korzina.svg" alt="">
            <img src="assets/img/header/person.svg" alt="">
        </div>

        <div class="burger_adaptiv_telephone">
            <img src="assets/img/header/korzina.svg" alt="">
            <img src="assets/img/header/person.svg" alt="">
        </div>

    </header>
    <!-- header end -->

    <!-- zakaz start -->
    <div class="zakaz container">
        <div class="profil_left">
            <div class="person">
                <img src="assets/img/profil/ava.svg" alt="">
                <p>Задыханова Амина</p>
            </div>

            <div class="fyltr_profil">
                <a class="perekluchat" href="profil.html">Профиль</a>
                <a class="perekluchat" href="zakaz.html">Заказы</a>
            </div>
        </div>

        <div class="zakaz_content">
            <h2>Заказы</h2>

            <div class="zakaz_count">
                <div class="zakaz_count_card">
                    <p>Статус</p>
                    <p>Количесво заказов</p>
                    <p>Дата и время</p>
                    <p>Итоговая цена</p>
                </div>

                <div class="zakaz_count_card">
                    <p>Статус</p>
                    <p>Количесво заказов</p>
                    <p>Дата и время</p>
                    <p>Итоговая цена</p>
                </div>

                <div class="zakaz_count_card">
                    <p>Статус</p>
                    <p>Количесво заказов</p>
                    <p>Дата и время</p>
                    <p>Итоговая цена</p>
                </div>

            </div>

        </div>
    </div>
    <!-- zakaz end -->

    <!-- footer start -->
    <footer class="my-120">
        <div class="footer_content container">

            <div class="stolbec_1">
                <div class="logo">
                    <img src="assets/img/footer/logo.svg" alt="">
                </div>

                <address>РТ, г. Казань, ул. Галеева, 3</address>

                <div class="prava">
                    <p>ООО «Сияние», ИНН 46534783244</p>
                    <p>2025 © Все права защищены</p>
                </div>
            </div>

            <div class="liniya"></div>

            <div class="stolbec_2">
                <h4>Горячая линия</h4>

                <div class="telephone">
                    <a href="tel:+72023223632">8-202-322-36-32</a>
                    <img src="assets/img/footer/icon1.svg" alt="">
                </div>

                <div class="news">
                    <h4>Следите за новостями</h4>
                    <div class="icons_news">
                        <img src="assets/img/footer/icon2.svg" alt="">
                        <img src="assets/img/footer/icon3.svg" alt="">
                    </div>
                </div>
            </div>

            <div class="liniya liniya_not"></div>

            <div class="stolbec_3">
                <h4>Магазин</h4>

                <nav>
                    <a href="#">Каталог</a>
                    <a href="#">Преимущества</a>
                    <a href="#">Новинки</a>
                    <a href="#">Категории</a>
                </nav>
            </div>

            <div class="liniya"></div>

            <div class="stolbec_4">
                <div class="documents">
                    <a href="assets/document/document.docx">О компании</a>
                    <a href="assets/document/document.docx">Политика конфиденциальности</a>
                    <a href="assets/document/document.docx">Правовая информация</a>
                </div>

                <p>Задыханова Амина, 2025</p>
            </div>
        </div>
    </footer>
    <!-- footer end -->
</body>

</html>