<?php

/** @var array  $categories */
/** @var array  $user_bets */

?>
<nav class="nav">

    <?= include_template('_partials/category-nav-list.php', [
        'categories' => $categories,
        'is_promo'   => false,
    ]) ?>

</nav>
<section class="rates container">
    <h2>Мои ставки</h2>

    <?php if (!empty($user_bets)): ?>
        <table class="rates__list">

            <?php foreach ($user_bets as $rate): ?>
                <?php $item_class_modifier = !empty($rate['is_win'])
                    ? 'rates__item--win'
                    : (!empty($rate['is_expired'] ? 'rates__item--end' : ''));
                ?>
                <tr class="rates__item <?= $item_class_modifier ?>">
                    <td class="rates__info">
                        <div class="rates__img">
                            <img
                                src="<?= esc($rate['image_url'] ?? '') ?>"
                                width="54"
                                height="40"
                                alt="<?= esc($rate['title'] ?? '') ?>">
                        </div>
                        <h3 class="rates__title">
                            <a href="lot.html">
                                <?= esc($rate['title'] ?? '') ?>
                            </a>
                        </h3>
                        <?php if (!empty($rate['is_win'])): ?>
                            <p><?= esc($rate['contact_info']) ?></p>
                        <?php endif; ?>
                    </td>
                    <td class="rates__category">
                        <?= esc($rate['category_name'] ?? '') ?>
                    </td>
                    <?php $time_left = get_time_left($lot['expire_date'] ?? ''); ?>
                    <div class="lot__timer timer<?= $time_left[0] === 0 ? ' timer--finishing' : '' ?>">
                        <?= format_time_left($time_left) ?>
                    </div>
                    <td class="rates__timer">
                        <?php if (!empty($rate['is_win'])): ?>
                            <div class="timer timer--win">Ставка выиграла</div>
                        <?php elseif (!empty($rate['is_lot_expired'])): ?>
                            <div class="timer timer--end">Торги окончены</div>
                        <?php else: ?>
                            <div class="timer timer<?= $time_left[0] === 0 ? ' timer--finishing' : '' ?>">
                                <?= format_time_left($time_left) ?>
                            </div>
                        <?php endif; ?>
                    </td>
                    <td class="rates__price">
                        <?php echo format_price($rate['max_amount'] ?? 0); ?>
                    </td>
                    <td class="rates__time">
                        5 минут назад
                    </td>
                </tr>

            <?php endforeach; ?>

        </table>
    <?php else: ?>
        <p>Нет ставок для отображения.</p>
    <?php endif; ?>
</section>