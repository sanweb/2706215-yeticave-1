<?php

/**
 * @var array $categories
 * @var string $list_class
 * @var string $item_class
 * @var string $link_class
 * @var bool $use_slug_modifier
 */

$link_class = $link_class ?? '';
$use_slug_modifier = $use_slug_modifier ?? false;

?>

<ul class="<?= esc($list_class) ?>">
    <?php foreach ($categories as $category): ?>
        <?php
        $item_classes = $item_class;

        if ($use_slug_modifier) {
            $slug = $category['slug'] ?? '';
            $item_classes .= " {$item_class}--{$slug}";
        }

        $category_id = (int) ($category['id'] ?? 0);
        $category_url = "/index.php?category_id={$category_id}";
        ?>

        <li class="<?= esc($item_classes) ?>">
            <a href="<?= esc($category_url) ?>" class="<?= esc($link_class) ?>">
                <?= esc($category['name'] ?? '') ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>