<?php

declare(strict_types=1);

require_once(__DIR__ . '/helpers.php');
require_once(__DIR__ . '/functions.php');

$isAuth = (bool) rand(0, 1);

$userName = 'Александр';

$categories = [
    'Доски и лыжи',
    'Крепления',
    'Ботинки',
    'Одежда',
    'Инструменты',
    'Разное'
];

$lots = [
    [
        'title' => '2014 Rossignol District Snowboard',
        'category' => 'Доски и лыжи',
        'price' => 10999,
        'image_url' => 'img/lot-1.jpg',
    ],
    [
        'title' => 'DC Ply Mens 2016/2017 Snowboard',
        'category' => 'Доски и лыжи',
        'price' => 159999,
        'image_url' => 'img/lot-2.jpg',
    ],
    [
        'title' => 'Крепления Union Contact Pro 2015 года размер L/XL',
        'category' => 'Крепления',
        'price' => 8000,
        'image_url' => 'img/lot-3.jpg',
    ],
    [
        'title' => 'Ботинки для сноуборда DC Mutiny Charcoal',
        'category' => 'Ботинки',
        'price' => 10999,
        'image_url' => 'img/lot-4.jpg',
    ],
    [
        'title' => 'Куртка для сноуборда DC Mutiny Charcoal',
        'category' => 'Одежда',
        'price' => 7500,
        'image_url' => 'img/lot-5.jpg',
    ],
    [
        'title' => 'Маска Oakley Canopy',
        'category' => 'Разное',
        'price' => 5400,
        'image_url' => 'img/lot-6.jpg',
    ],
];

$mainContent = include_template('main.php', [
    'lots' => $lots,
    'categories' => $categories,
]);

$pageContent = include_template('layout.php', [
    'is_auth' => $isAuth,
    'user_name' => $userName,
    'categories' => $categories,
    'content' => $mainContent,
    'title' => 'Главная',
]);

echo $pageContent;
