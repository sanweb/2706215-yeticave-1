<?php

/** @var array $categories */
/** @var array $lots */

?>
<section class="promo">
    <h2 class="promo__title">Нужен стафф для катки?</h2>
    <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>

    <?= include_template('_partials/category-nav-list.php', [
        'categories' => $categories,
        'is_promo' => true,
    ]) ?>

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
                    <span class="lot__category"><?= esc($lot['category_name'] ?? '') ?></span>
                    <h3 class="lot__title">
                        <a class="text-link" href="/lot.php?id=<?= (int) ($lot['id'] ?? 0) ?>"><?= esc($lot['title'] ?? '') ?></a>
                    </h3>
                    <div class="lot__state">
                        <div class="lot__rate">
                            <span class="lot__amount">Стартовая цена</span>
                            <span class="lot__cost"><?= format_price($lot['price'] ?? 0) ?></span>
                        </div>

                        <?php $time_left = get_time_left($lot['expire_date'] ?? ''); ?>
                        <div class="lot__timer timer<?= $time_left[0] === 0 ? ' timer--finishing' : '' ?>">
                            <?= format_time_left($time_left) ?>
                        </div>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>

    </ul>
</section>
