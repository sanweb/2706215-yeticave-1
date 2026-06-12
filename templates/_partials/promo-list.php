<?php

/** @var array $categories */

?>
<ul class="promo__list">

    <?php foreach ($categories as $category): ?>
        <li class="promo__item promo__item--<?= esc($category['slug'] ?? '') ?>">
            <a
                class="promo__link"
                href="/all-lots.php?category_id=<?= (int) ($category['id'] ?? 0) ?>"
            >
                <?= esc($category['name'] ?? '') ?>
            </a>
        </li>
    <?php endforeach; ?>

</ul>
