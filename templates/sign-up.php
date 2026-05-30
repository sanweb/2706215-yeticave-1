<?php

/** @var array $categories */
/** @var array $lot */
/** @var array $form_data */
/** @var array $form_errors */

?>
<nav class="nav">

    <?= include_template('_partials/category-nav-list.php', [
        'categories' => $categories,
        'is_promo'   => false,
    ]) ?>

</nav>
<?php $form_name = 'create-user-form'; ?>
<form
    class="form container<?= !empty($form_errors) ? ' form--invalid' : '' ?>"
    action="sign-up.php"
    method="post"
    name="<?= $form_name ?>"
    enctype="application/x-www-form-urlencoded"
    autocomplete="off">
    <h2>Регистрация нового аккаунта</h2>

    <?php $field_name = 'email'; ?>
    <div class="form__item<?= !empty($form_errors[$field_name]) ? ' form__item--invalid' : '' ?>">
        <label for="<?= build_form_field_id($form_name, $field_name) ?>">E-mail <sup>*</sup></label>
        <input
            id="<?= build_form_field_id($form_name, $field_name) ?>"
            type="text"
            name="<?= $field_name ?>"
            value="<?= esc($form_data[$field_name] ?? '') ?>"
            placeholder="Введите e-mail">
        <span class="form__error"><?= $form_errors[$field_name] ?? '' ?></span>
    </div>

    <?php $field_name = 'password'; ?>
    <div class="form__item<?= !empty($form_errors[$field_name]) ? ' form__item--invalid' : '' ?>">
        <label for="<?= build_form_field_id($form_name, $field_name) ?>">Пароль <sup>*</sup></label>
        <input
            id="<?= build_form_field_id($form_name, $field_name) ?>"
            type="password"
            name="<?= $field_name ?>"
            value="<?= esc($form_data[$field_name] ?? '') ?>"
            placeholder="Введите пароль">
        <span class="form__error"><?= $form_errors[$field_name] ?? '' ?></span>
    </div>

    <?php $field_name = 'name'; ?>
    <div class="form__item<?= !empty($form_errors[$field_name]) ? ' form__item--invalid' : '' ?>">
        <label for="<?= build_form_field_id($form_name, $field_name) ?>">Имя <sup>*</sup></label>
        <input
            id="<?= build_form_field_id($form_name, $field_name) ?>"
            type="text"
            name="<?= $field_name ?>"
            value="<?= esc($form_data[$field_name] ?? '') ?>"
            placeholder="Введите имя">
        <span class="form__error"><?= $form_errors[$field_name] ?? '' ?></span>
    </div>

    <?php $field_name = 'contact_info'; ?>
    <div class="form__item<?= !empty($form_errors[$field_name]) ? ' form__item--invalid' : '' ?>">
        <label for="<?= build_form_field_id($form_name, $field_name) ?>">Контактные данные <sup>*</sup></label>
        <textarea
            id="<?= build_form_field_id($form_name, $field_name) ?>"
            name="<?= $field_name ?>"
            placeholder="Напишите как с вами связаться"><?= esc($form_data[$field_name] ?? '') ?></textarea>
        <span class="form__error"><?= $form_errors[$field_name] ?? '' ?></span>
    </div>

    <?php if (!empty($form_errors)): ?>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <?php endif; ?>

    <button type="submit" class="button">Зарегистрироваться</button>
    <a class="text-link" href="#">Уже есть аккаунт</a>
</form>