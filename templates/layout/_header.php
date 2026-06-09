<?php

/** @var array  $user */
/** @var string $search_phrase */

?>
<header class="main-header">
    <div class="main-header__container container">
        <h1 class="visually-hidden">YetiCave</h1>
        <a class="main-header__logo" <?= !is_home_page() ? 'href="/"' : '' ?>>
            <img src="/assets/img/logo.svg" width="160" height="39" alt="Логотип компании YetiCave">
        </a>
        <form class="main-header__search" method="get" action="/search.php" autocomplete="off">
            <!--suppress HtmlFormInputWithoutLabel -->
            <input type="search" name="search" value="<?= $search_phrase ?> "placeholder="Поиск лота">
            <input class="main-header__search-btn" type="submit" value="Найти">
        </form>
        <a class="main-header__add-lot button" href="/add.php">Добавить лот</a>

        <nav class="user-menu">

            <?php if (is_auth()): ?>
                <div class="user-menu__logged">
                    <p><?= esc($user['name'] ?? '') ?></p>
                    <a class="user-menu__bets user-menu" href="/my-bets.php">Мои ставки</a>
                    <a class="user-menu__logout" href="/logout.php">Выход</a>
                </div>
            <?php else: ?>
                <ul class="user-menu__list">
                    <li class="user-menu__item">
                        <a href="/sign-up.php">Регистрация</a>
                    </li>
                    <li class="user-menu__item">
                        <a href="/login.php">Вход</a>
                    </li>
                </ul>
            <?php endif; ?>

        </nav>
    </div>
</header>
