<?php

/** @var string  $user_name */
/** @var string  $lot_url */
/** @var string  $lot_title */
/** @var string  $my_bets_url */

?>
<h1>Поздравляем с победой</h1>
<p>Здравствуйте, <?= esc($user_name) ?></p>
<p>Ваша ставка для лота <a href="<?= esc($lot_url) ?>"><?= esc($lot_title) ?></a> победила.</p>
<p>Перейдите по ссылке <a href="<?= esc($my_bets_url) ?>">мои ставки</a>, чтобы связаться с автором объявления</p>
<small>Интернет-Аукцион "YetiCave"</small>
