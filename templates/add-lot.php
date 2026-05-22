<?php

/** @var array $categories */
/** @var array $lot */
/** @var array $form_data */
/** @var array $form_errors */

?>
<nav class="nav">

    <?= include_template('_partials/category-nav-list.php', [
        'categories' => $categories,
        'is_promo' => false,
    ]) ?>

</nav>
<form
    class="form form--add-lot container<?= !empty($form_errors) ? ' form--invalid' : '' ?>"
    action="add.php"
    method="post"
    enctype="multipart/form-data">
    <h2>Добавление лота</h2>
    <div class="form__container-two">

        <?php $field_name = 'title'; ?>
        <div class="form__item<?= !empty($form_errors[$field_name]) ? ' form__item--invalid' : '' ?>">
            <label for="lot-name">Наименование <sup>*</sup></label>
            <input
                id="lot-name"
                type="text"
                name="<?= $field_name ?>"
                value="<?= esc($form_data[$field_name] ?? '') ?>"
                placeholder="Введите наименование лота">
            <span class="form__error">Введите наименование лота</span>
        </div>

        <?php $field_name = 'category_id'; ?>
        <div class="form__item<?= !empty($form_errors[$field_name]) ? ' form__item--invalid' : '' ?>">
            <label for="category">Категория <sup>*</sup></label>
            <select id="category" name="<?= $field_name ?>">
                <option value="">Выберите категорию</option>

                <?php foreach ($categories as $category): ?>
                    <option
                        value="<?= esc($category['id'] ?? '') ?>"
                        <?= ($form_data[$field_name] ?? '') === ($category['id'] ?? '') ? ' selected' : '' ?>>
                        <?= esc($category['name'] ?? '') ?>
                    </option>
                <?php endforeach; ?>

            </select>

            <span class="form__error">Выберите категорию</span>
        </div>
    </div>

    <?php $field_name = 'description'; ?>
    <div class="form__item form__item--wide<?= !empty($form_errors[$field_name]) ? ' form__item--invalid' : '' ?>">
        <label for="message">Описание <sup>*</sup></label>
        <textarea
            id="message"
            name="<?= $field_name ?>"
            placeholder="Напишите описание лота"><?= esc($form_data[$field_name] ?? '') ?></textarea>
        <span class="form__error">Напишите описание лота</span>
    </div>

    <?php $field_name = 'lot_image_file'; ?>
    <div class="form__item form__item--file<?= !empty($form_errors[$field_name]) ? ' form__item--invalid' : '' ?>">
        <label>Изображение <sup>*</sup></label>
        <div class="form__input-file">
            <?php $allowed_image_types = implode(', ', array_keys(ALLOWED_IMAGE_TYPES)); ?>
            <input
                class="visually-hidden"
                type="file"
                name="<?= $field_name ?>"
                id="lot-img"
                value=""
                accept="<?= $allowed_image_types ?>">
            <label for="lot-img">Добавить</label>
        </div>
        <span class="form__error">Загрузите изображение</span>
    </div>

    <div class="form__container-three">

        <?php $field_name = 'start_price'; ?>
        <div class="form__item form__item--small<?= !empty($form_errors[$field_name]) ? ' form__item--invalid' : '' ?>">
            <label for="lot-rate">Начальная цена <sup>*</sup></label>
            <input
                id="lot-rate"
                type="text"
                name="<?= $field_name ?>"
                value="<?= esc($form_data[$field_name] ?? '') ?>"
                placeholder="0">
            <span class="form__error">Введите начальную цену</span>
        </div>

        <?php $field_name = 'bet_step'; ?>
        <div class="form__item form__item--small<?= !empty($form_errors[$field_name]) ? ' form__item--invalid' : '' ?>">
            <label for="lot-step">Шаг ставки <sup>*</sup></label>
            <input
                id="lot-step"
                type="text"
                name="<?= $field_name ?>"
                value="<?= esc($form_data[$field_name] ?? '') ?>"
                placeholder="0">
            <span class="form__error">Введите шаг ставки</span>
        </div>

        <?php $field_name = 'expire_date'; ?>
        <div class="form__item<?= !empty($form_errors[$field_name]) ? ' form__item--invalid' : '' ?>">
            <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
            <input
                class="form__input-date"
                id="lot-date"
                type="text"
                name="<?= $field_name ?>"
                value="<?= esc($form_data[$field_name] ?? '') ?>"
                placeholder="Введите дату в формате ГГГГ-ММ-ДД">
            <span class="form__error">Введите дату завершения торгов</span>
        </div>
    </div>

    <?php if (!empty($form_errors)): ?>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <?php endif; ?>

    <button type="submit" class="button">Добавить лот</button>
</form>