<?php

/** @var array  $categories */
/** @var array  $lot */
/** @var bool   $is_bet_form_available */
/** @var string $form_name */
/** @var array  $form_data */
/** @var array  $form_errors */

?>
<nav class="nav">

    <?= include_template('_partials/nav-list.php', [
        'categories' => $categories,
    ]) ?>

</nav>
<section class="lot-item container">
    <h2><?= esc($lot['title'] ?? '') ?></h2>
    <div class="lot-item__content">
        <div class="lot-item__left">
            <div class="lot-item__image">
                <img
                    src="<?= esc($lot['image_url'] ?? '') ?>"
                    width="730"
                    height="548"
                    alt="<?= esc($lot['title'] ?? '') ?>">
            </div>
            <p class="lot-item__category">Категория: <span><?= esc($lot['category_name'] ?? '') ?></span></p>
            <p class="lot-item__description"><?= esc($lot['description'] ?? '') ?></p>
        </div>
        <div class="lot-item__right">
            <div class="lot-item__state">
                <?php $time_left = get_time_left($lot['expire_date'] ?? ''); ?>
                <div class="lot-item__timer timer<?= $time_left[0] === 0 ? ' timer--finishing' : '' ?>">
                    <?= format_time_left($time_left) ?>
                </div>
                <div class="lot-item__cost-state">
                    <div class="lot-item__rate">
                        <span class="lot-item__amount">Текущая цена</span>
                        <span class="lot-item__cost"><?= format_price($lot['price'] ?? 0, false) ?></span>
                    </div>
                    <div class="lot-item__min-cost">
                        Мин. ставка <span><?= format_price($lot['min_bet'] ?? 0, true, ' р') ?></span>
                    </div>
                </div>

                <?php if ($is_bet_form_available): ?>
                    <form class="lot-item__form<?= !empty($form_errors) ? ' form--invalid' : '' ?>" action="" method="post" autocomplete="off">
                        <?php $field_name = 'amount'; ?>
                        <?php $field_id = build_form_field_id($form_name, $field_name); ?>
                        <p class="lot-item__form-item form__item<?= !empty($form_errors[$field_name]) ? ' form__item--invalid' : '' ?>">
                            <label for="<?= $field_id ?>">Ваша ставка</label>
                            <input
                                id="<?= $field_id ?>"
                                type="text"
                                name="<?= $field_name ?>"
                                value="<?= esc($form_data[$field_name] ?? '') ?>"
                                placeholder="<?= esc($lot['min_bet'] ?? '') ?>"
                            >
                            <span class="form__error"><?= $form_errors[$field_name] ?? '' ?></span>
                        </p>
                        <button type="submit" class="button">Сделать ставку</button>
                    </form>
                <?php endif; ?>

            </div>
            <!-- Bet history (not required yet) -->
        </div>
    </div>
</section>
