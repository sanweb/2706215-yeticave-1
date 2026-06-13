<?php

/** @var array $categories */
/** @var array $category */
/** @var array $lots */
/** @var array $pagination */
/** @var int   $current_page */

?>
<nav class="nav">
    <?= include_template('_partials/nav-list.php', [
        'categories'          => $categories,
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
                    <?= include_template('_partials/lot-card.php', ['lot' => $lot]) ?>
                <?php endforeach; ?>

            </ul>
        <?php else: ?>
            <p>Нет активных лотов в данной категории</p>
        <?php endif; ?>
    </section>

    <?php if (!empty($pagination['pages'])): ?>
        <?= include_template('_partials/pagination.php', [
            'pagination'   => $pagination,
            'current_page' => $current_page,
        ]) ?>
    <?php endif; ?>
</div>
