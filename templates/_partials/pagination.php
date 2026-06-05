<?php

/** @var array $pagination */
/** @var int $current_page */

?>
<ul class="pagination-list">

    <li class="pagination-item pagination-item-prev">
        <a <?= $pagination['prev'] ? 'href="' . $pagination['prev'] . '"' : '' ?>>Назад</a>
    </li>

    <?php if (!empty($pagination['pages'])): ?>

        <?php foreach ($pagination['pages'] as $page => $page_url): ?>

            <?php if ($current_page === $page): ?>
                <li class="pagination-item pagination-item-active"><a><?= $page ?></a></li>
            <?php else: ?>
                <li class="pagination-item"><a href="<?= $page_url ?>"><?= $page ?></a></li>
            <?php endif; ?>

        <?php endforeach; ?>

    <?php endif; ?>

    <li class="pagination-item pagination-item-next">
        <a <?= $pagination['next'] ? 'href="' . $pagination['next'] . '"' : '' ?>>Вперед</a>
    </li>
</ul>
