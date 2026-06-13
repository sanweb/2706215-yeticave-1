<?php

/** @var array $categories */
/** @var array $lots */

?>
<section class="promo">
    <h2 class="promo__title">Нужен стафф для катки?</h2>
    <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
    <?= include_template('_partials/promo-list.php', compact('categories')) ?>
</section>

<section class="lots">
    <div class="lots__header">
        <h2>Открытые лоты</h2>
    </div>

    <ul class="lots__list">
        <?php foreach ($lots as $lot): ?>
            <?= include_template('_partials/lot-card.php', compact('lot')) ?>
        <?php endforeach; ?>
    </ul>
</section>
