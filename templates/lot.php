<?php

/** @var array $categories */
/** @var array $lot */

?>
<nav class="nav">

    <?= include_template('_partials/category-nav-list.php', [
        'categories' => $categories,
        'is_promo' => false,
    ]) ?>

</nav>
<section class="lot-item container">
    <h2><?= esc($lot['title'] ?? '') ?></h2>
    <div class="lot-item__content">
        <div class="lot-item__left">
            <div class="lot-item__image">
                <img
                    src="<?= esc($lot['image_url'] ?? '') ?>"
                    width="730"
                    height="548"
                    alt="<?= esc($lot['title'] ?? '') ?>">
            </div>
            <p class="lot-item__category">Категория: <span><?= esc($lot['category_name'] ?? '') ?></span></p>
            <p class="lot-item__description"><?= esc($lot['description'] ?? '') ?></p>
        </div>
        <div class="lot-item__right">
            <div class="lot-item__state">
                <?php $time_left = get_time_left($lot['expire_date'] ?? ''); ?>
                <div class="lot-item__timer timer<?= $time_left[0] === 0 ? ' timer--finishing' : '' ?>">
                    <?= format_time_left($time_left) ?>
                </div>
                <div class="lot-item__cost-state">
                    <div class="lot-item__rate">
                        <span class="lot-item__amount">Текущая цена</span>
                        <span class="lot-item__cost"><?= format_price($lot['price'] ?? 0) ?></span>
                    </div>
                    <div class="lot-item__min-cost">
                        Мин. ставка <span><?= format_price($lot['min_bet'] ?? 0) ?></span>
                    </div>
                </div>
                <?php if (is_auth() && ($lot['author_id'] ?? 0) !== get_user_id()): ?>
                    <!-- Bet form (not required yet) -->
                <?php endif; ?>
            </div>
            <!-- Bet history (not required yet) -->
        </div>
    </div>
</section>