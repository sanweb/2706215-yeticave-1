<?php

/** @var array  $categories */
/** @var array  $user_bets */

?>
<nav class="nav">
    <?= include_template('_partials/nav-list.php', compact('categories')) ?>
</nav>

<section class="rates container">
    <h2>Мои ставки</h2>

    <?php if (!empty($user_bets)): ?>
        <table class="rates__list">

            <?php foreach ($user_bets as $bet): ?>
                <?php $item_class_modifier = !empty($bet['is_win'])
                    ? ' rates__item--win'
                    : (!empty($bet['is_expired']) ? ' rates__item--end' : '');
                ?>
                <tr class="rates__item<?= $item_class_modifier ?>">
                    <td class="rates__info">
                        <div class="rates__img">
                            <img
                                src="<?= esc($bet['image_url'] ?? '') ?>"
                                width="54"
                                height="40"
                                alt="<?= esc($bet['title'] ?? '') ?>"
                            >
                        </div>

                        <div>
                            <h3 class="rates__title">
                                <a href="/lot.php?id=<?= (int) ($bet['lot_id'] ?? 0) ?>">
                                    <?= esc($bet['title'] ?? '') ?>
                                </a>
                            </h3>

                            <?php if (!empty($bet['is_win'])): ?>
                                <p><?= esc($bet['contact_info']) ?></p>
                            <?php endif; ?>
                        </div>
                    </td>

                    <td class="rates__category">
                        <?= esc($bet['category_name'] ?? '') ?>
                    </td>

                    <td class="rates__timer">
                        <?php if (!empty($bet['is_win'])): ?>
                            <div class="timer timer--win">Ставка выиграла</div>
                        <?php elseif (!empty($bet['is_expired'])): ?>
                            <div class="timer timer--end">Торги окончены</div>
                        <?php else: ?>
                            <?php $time_left = get_time_left($bet['expire_date'] ?? ''); ?>
                            <div class="timer timer<?= $time_left[0] === 0 ? ' timer--finishing' : '' ?>">
                                <?= format_time_left($time_left) ?>
                            </div>
                        <?php endif; ?>
                    </td>

                    <td class="rates__price">
                        <?php echo format_price($bet['bet_amount'] ?? 0, true, ' р'); ?>
                    </td>

                    <td class="rates__time">
                        <?= format_time_since_bet($bet['bet_created_at']) ?>
                    </td>
                </tr>
            <?php endforeach; ?>

        </table>
    <?php else: ?>
        <p>Нет ставок для отображения.</p>
    <?php endif; ?>
</section>
