<?php

/** @var string $page_title */
/** @var array  $user */
/** @var string $main_classname */
/** @var string $main_content */
/** @var array  $categories */
/** @var array  $css_files */
/** @var array  $js_files */

$css_files = $css_files ?? [];
$js_files = $js_files ?? [];

?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title><?= esc($page_title) ?></title>
    <!-- common styles -->
    <link href="/assets/css/normalize.min.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
    <!-- page styles -->
    <?= include_asset_files(ASSET_TYPE_CSS, $css_files) ?>
</head>

<body>
    <div class="page-wrapper">

        <?= include_template('layout/_header.php', [
            'user' => $user,
            'search_phrase' => $search_phrase ?? '',
        ]) ?>

        <main class="<?= $main_classname ?>">
            <?= $main_content ?>
        </main>
    </div>

    <?= include_template('layout/_footer.php', [
        'categories' => $categories,
    ]) ?>

    <!-- page js -->
    <?= include_asset_files(ASSET_TYPE_JS, $js_files) ?>
</body>

</html>
