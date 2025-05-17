<!-- header start -->
<header class="container">

    <nav class="burger_desctop">
        <a href="./?page=katalog">Каталог</a>
        <a href="#">Коллекции</a>
        <a href="#">Новинки</a>
        <a href="#">Категории</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <?php if ($USER['role'] === 'admin'): ?>    
                <a href="./?page=admin_tovar">Редакт</a>
            <?php else: ?>
                <a href="./?page=profil">Профиль</a>
            <?php endif; ?>
            <a href=""></a>
        <?php endif; ?>

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
        <a href="./"><img src="assets/img/header/logo.svg" alt=""></a>
    </div>

    <nav class="burger_desctop">
        <div class="poisk">
            <img src="assets/img/header/lupa.svg" alt="">
            <input type="text" placeholder="Поиск...">
        </div>
        <a href="./?page=korzina"><img src="assets/img/header/korzina.svg" alt=""></a>
        <a href="./?page=register"><img src="assets/img/header/person.svg" alt=""></a>
    </nav>

    <div class="burger_adaptiv">
        <img src="assets/img/header/korzina.svg" alt="">
        <img src="assets/img/header/person.svg" alt="">
    </div>

    <div class="burger_adaptiv_telephone">
        <a href="assets/img/header/korzina.svg"><img src="assets/img/header/korzina.svg" alt=""></a>
        <a href=""><img src="assets/img/header/person.svg" alt=""></a>
    </div>

</header>
<!-- header end -->