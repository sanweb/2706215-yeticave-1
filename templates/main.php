<?php

/** @var array $categories */
/** @var array $lots */

?>
<section class="promo">
    <h2 class="promo__title">Нужен стафф для катки?</h2>
    <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
    <ul class="promo__list">

        <?php foreach ($categories as $category): ?>
            <li class="promo__item promo__item--boards">
                <a class="promo__link" href="pages/all-lots.html"><?= esc($category) ?></a>
            </li>
        <?php endforeach; ?>

    </ul>
</section>
<section class="lots">
    <div class="lots__header">
        <h2>Открытые лоты</h2>
    </div>
    <ul class="lots__list">

        <?php foreach ($lots as $lot): ?>
            <li class="lots__item lot">
                <div class="lot__image">
                    <img
                        src="<?= esc($lot['image_url'] ?? '') ?>"
                        width="350"
                        height="260"
                        alt="<?= esc($lot['title'] ?? '') ?>"
                    >
                </div>

                <div class="lot__info">
                    <span class="lot__category"><?= esc($lot['category'] ?? '') ?></span>
                    <h3 class="lot__title">
                        <a class="text-link" href="pages/lot.html"><?= esc($lot['title'] ?? '') ?></a>
                    </h3>
                    <div class="lot__state">
                        <div class="lot__rate">
                            <span class="lot__amount"><?= format_price($lot['price'] ?? 0) ?></span>
                            <span class="lot__cost">цена</span>
                        </div>

                        <?php $time_left = get_dt_range($lot['expire_date'] ?? ''); ?>
                        <div class="lot__timer timer<?= $time_left[0] === 0 ? ' timer--finishing' : '' ?>">
                            <?= format_time_left($time_left) ?>
                        </div>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>

    </ul>
</section>