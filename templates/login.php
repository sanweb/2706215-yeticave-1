<?php

/** @var array  $categories */
/** @var array  $lot */
/** @var string $form_name */
/** @var array  $form_data */
/** @var array  $form_errors */

?>
<nav class="nav">

    <?= include_template('_partials/nav-list.php', [
        'categories' => $categories,
    ]) ?>

</nav>
<form
    class="form container<?= !empty($form_errors) ? ' form--invalid' : '' ?>"
    action="login.php"
    method="post"
    name="<?= $form_name ?>"
    enctype="application/x-www-form-urlencoded"
>
    <h2>Вход</h2>

    <?php $field_name = 'email'; ?>
    <?php $field_id = build_form_field_id($form_name, $field_name); ?>
    <div class="form__item<?= !empty($form_errors[$field_name]) ? ' form__item--invalid' : '' ?>">
        <label for="<?= $field_id ?>">E-mail <sup>*</sup></label>
        <input
            id="<?= $field_id ?>"
            type="text"
            name="<?= $field_name ?>"
            value="<?= esc($form_data[$field_name] ?? '') ?>"
            placeholder="Введите e-mail"
        >
        <span class="form__error"><?= $form_errors[$field_name] ?? '' ?></span>
    </div>

    <?php $field_name = 'password'; ?>
    <?php $field_id = build_form_field_id($form_name, $field_name); ?>
    <div class="form__item form__item--last<?= !empty($form_errors[$field_name]) ? ' form__item--invalid' : '' ?>">
        <label for="<?= $field_id ?>">Пароль <sup>*</sup></label>
        <input
            id="<?= $field_id ?>"
            type="password"
            name="<?= $field_name ?>"
            value="<?= esc($form_data[$field_name] ?? '') ?>"
            placeholder="Введите пароль"
        >
        <span class="form__error"><?= $form_errors[$field_name] ?? '' ?></span>
    </div>

    <?php if (!empty($form_errors)): ?>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <?php endif; ?>

    <button type="submit" class="button">Войти</button>
</form>
