<?php

/** @var array  $categories */
/** @var bool   $is_promo */

?>

<ul class="<?= $is_promo ? 'promo__list' : 'nav__list container' ?>">

    <?php foreach ($categories as $category): ?>
        <li class="<?= $is_promo ? ('promo__item promo__item--' . esc($category['slug'] ?? '')) : 'nav__item' ?>">
            <a
                class="<?= $is_promo ? 'promo__link' : '' ?>"
                href="/index.php?category_id=<?= (int) ($category['id'] ?? 0) ?>"
            >
                <?= esc($category['name'] ?? '') ?>
            </a>
        </li>
    <?php endforeach; ?>

</ul>
