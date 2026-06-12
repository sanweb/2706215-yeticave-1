<?php

/** @var array $categories */
/** @var array $category */
/** @var array $lots */
/** @var array $pagination */
/** @var int   $current_page */

?>
<nav class="nav">

    <?= include_template('_partials/nav-list.php', [
        'categories' => $categories,
        'current_category_id' => (int) ($category['id'] ?? 0),
    ]) ?>

</nav>
<div class="container">
    <section class="lots">
        <div class="lots__header">
            <h2>Все лоты в категории «<span><?= esc($category['name'] ?? '') ?></span>»</h2>
        </div>

        <?php if (!empty($lots)): ?>
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
                                <a class="text-link" href="/lot.php?id=<?= (int) ($lot['id'] ?? 0) ?>">
                                    <?= esc($lot['title'] ?? '') ?>
                                </a>
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
        <?php else: ?>
            <p>Нет активных лотов в данной категории</p>
        <?php endif; ?>

    </section>

    <?php if (!empty($pagination['pages'])): ?>

        <?= include_template('_partials/pagination.php', [
            'pagination' => $pagination,
            'current_page' => $current_page,
        ]) ?>

    <?php endif; ?>

</div>
