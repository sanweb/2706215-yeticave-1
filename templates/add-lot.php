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
    class="form form--add-lot container<?= !empty($form_errors) ? ' form--invalid' : '' ?>"
    action="add.php"
    method="post"
    enctype="multipart/form-data"
>
    <h2>Добавление лота</h2>
    <div class="form__container-two">

        <?php $field_name = 'title'; ?>
        <?php $field_id = build_form_field_id($form_name, $field_name); ?>
        <div class="form__item<?= !empty($form_errors[$field_name]) ? ' form__item--invalid' : '' ?>">
            <label for="<?= $field_id ?>">Наименование <sup>*</sup></label>
            <input
                id="<?= $field_id ?>"
                type="text"
                name="<?= $field_name ?>"
                value="<?= esc($form_data[$field_name] ?? '') ?>"
                placeholder="Введите наименование лота"
            >
            <span class="form__error"><?= $form_errors[$field_name] ?? '' ?></span>
        </div>

        <?php $field_name = 'category_id'; ?>
        <?php $field_id = build_form_field_id($form_name, $field_name); ?>
        <div class="form__item<?= !empty($form_errors[$field_name]) ? ' form__item--invalid' : '' ?>">
            <label for="<?= $field_id ?>">Категория <sup>*</sup></label>
            <select id="<?= $field_id ?>" name="<?= $field_name ?>">
                <option value="">Выберите категорию</option>

                <?php foreach ($categories as $category): ?>
                    <option
                        value="<?= esc($category['id'] ?? '') ?>"
                        <?= ($form_data[$field_name] ?? '') === ($category['id'] ?? '') ? ' selected' : '' ?>>
                        <?= esc($category['name'] ?? '') ?>
                    </option>
                <?php endforeach; ?>

            </select>

            <span class="form__error"><?= $form_errors[$field_name] ?? '' ?></span>
        </div>
    </div>

    <?php $field_name = 'description'; ?>
    <?php $field_id = build_form_field_id($form_name, $field_name); ?>
    <div class="form__item form__item--wide<?= !empty($form_errors[$field_name]) ? ' form__item--invalid' : '' ?>">
        <label for="<?= $field_id ?>">Описание <sup>*</sup></label>
        <textarea
            id="<?= $field_id ?>"
            name="<?= $field_name ?>"
            placeholder="Напишите описание лота"
        ><?= esc($form_data[$field_name] ?? '') ?></textarea>
        <span class="form__error"><?= $form_errors[$field_name] ?? '' ?></span>
    </div>

    <?php $field_name = 'lot_image_file'; ?>
    <?php $field_id = build_form_field_id($form_name, $field_name); ?>
    <div class="form__item form__item--file<?= !empty($form_errors[$field_name]) ? ' form__item--invalid' : '' ?>">
        <label>Изображение <sup>*</sup></label>
        <div class="form__input-file">
            <?php $allowed_image_types = implode(', ', array_keys(ALLOWED_IMAGE_TYPES)); ?>
            <input
                class="visually-hidden"
                type="file"
                name="<?= $field_name ?>"
                id="<?= $field_id ?>"
                value=""
                accept="<?= $allowed_image_types ?>"
            >
            <label for="<?= $field_id ?>">Добавить</label>
        </div>
        <span class="form__error"><?= $form_errors[$field_name] ?? '' ?></span>
    </div>

    <div class="form__container-three">

        <?php $field_name = 'start_price'; ?>
        <?php $field_id = build_form_field_id($form_name, $field_name); ?>
        <div class="form__item form__item--small<?= !empty($form_errors[$field_name]) ? ' form__item--invalid' : '' ?>">
            <label for="<?= $field_id ?>">Начальная цена <sup>*</sup></label>
            <input
                id="<?= $field_id ?>"
                type="text"
                name="<?= $field_name ?>"
                value="<?= esc($form_data[$field_name] ?? '') ?>"
                placeholder="0"
            >
            <span class="form__error">Введите начальную цену</span>
        </div>

        <?php $field_name = 'bet_step'; ?>
        <?php $field_id = build_form_field_id($form_name, $field_name); ?>
        <div class="form__item form__item--small<?= !empty($form_errors[$field_name]) ? ' form__item--invalid' : '' ?>">
            <label for="<?= $field_id ?>">Шаг ставки <sup>*</sup></label>
            <input
                id="<?= $field_id ?>"
                type="text"
                name="<?= $field_name ?>"
                value="<?= esc($form_data[$field_name] ?? '') ?>"
                placeholder="0"
            >
            <span class="form__error"><?= $form_errors[$field_name] ?? '' ?></span>
        </div>

        <?php $field_name = 'expire_date'; ?>
        <?php $field_id = build_form_field_id($form_name, $field_name); ?>
        <div class="form__item<?= !empty($form_errors[$field_name]) ? ' form__item--invalid' : '' ?>">
            <label for="<?= $field_id ?>">Дата окончания торгов <sup>*</sup></label>
            <input
                class="form__input-date"
                id="<?= $field_id ?>"
                type="text"
                name="<?= $field_name ?>"
                value="<?= esc($form_data[$field_name] ?? '') ?>"
                placeholder="Введите дату в формате ГГГГ-ММ-ДД"
            >
            <span class="form__error"><?= $form_errors[$field_name] ?? '' ?></span>
        </div>
    </div>

    <?php if (!empty($form_errors)): ?>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <?php endif; ?>

    <button type="submit" class="button">Добавить лот</button>
</form>
