<?php

/** @var array  $categories */
/** @var string $search_phrase */
/** @var array  $search_results */
/** @var array  $pagination */
/** @var int    $current_page */

?>
<nav class="nav">

    <?= include_template('_partials/nav-list.php', [
        'categories' => $categories,
    ]) ?>

</nav>
<div class="container">
    <section class="lots">
        <div class="lots__header">
            <h2>
                Результаты поиска
                <?php if ($search_phrase): ?> по запросу «<span><?= $search_phrase ?></span>»<?php endif; ?>
            </h2>
        </div>

        <?php if (!empty($search_results)): ?>
            <ul class="lots__list">

                <?php foreach ($search_results as $lot): ?>
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
        <?php elseif ($search_phrase !== ''): ?>
            <p>Ничего не найдено по вашему запросу</p>
        <?php endif; ?>

    </section>

    <?php if (!empty($pagination['pages'])): ?>

        <?= include_template('_partials/pagination.php', [
            'pagination' => $pagination,
            'current_page' => $current_page,
        ]) ?>

    <?php endif; ?>

</div>
