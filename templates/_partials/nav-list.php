<?php

/** @var array $categories */

?>
<ul class="nav__list container">

    <?php foreach ($categories as $category): ?>
        <?php
        $is_active = !empty($current_category_id)
            && intval($category['id'] ?? 0) === $current_category_id;
        ?>
        <li class="nav__item<?= $is_active ? ' nav__item--current' : '' ?>">
            <a href="/all-lots.php?category_id=<?= (int) ($category['id'] ?? 0) ?>">
                <?= esc($category['name'] ?? '') ?>
            </a>
        </li>
    <?php endforeach; ?>

</ul>
