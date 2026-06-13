<?php

/** @var array  $categories */
/** @var string $search_phrase */
/** @var array  $search_results */
/** @var array  $pagination */
/** @var int    $current_page */

?>
<nav class="nav">
    <?= include_template('_partials/nav-list.php', compact('categories')) ?>
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
                    <?= include_template('_partials/lot-card.php', ['lot' => $lot]) ?>
                <?php endforeach; ?>

            </ul>
        <?php elseif ($search_phrase !== ''): ?>
            <p>Ничего не найдено по вашему запросу</p>
        <?php endif; ?>
    </section>

    <?php if (!empty($pagination['pages'])): ?>
        <?= include_template('_partials/pagination.php', compact(
            'pagination',
            'current_page'
        )) ?>
    <?php endif; ?>

</div>
