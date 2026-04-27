<?php

declare(strict_types=1);

/**
 * @var list<string> $categories List of lot categories.
 */
$categories = [
    'Доски и лыжи',
    'Крепления',
    'Ботинки',
    'Одежда',
    'Инструменты',
    'Разное'
];

/**
 * List of lots.
 *
 * Each lot has an expiration date in Y-m-d format.
 *
 * @var list<array{
 *     title: string,
 *     category: string,
 *     price: int,
 *     image_url: string,
 *     expire_date: string
 * }> $lots
 */
$lots = [
    [
        'title' => '2014 Rossignol District Snowboard',
        'category' => 'Доски и лыжи',
        'price' => 10999,
        'image_url' => 'img/lot-1.jpg',
        'expire_date' => '2026-04-15',
    ],
    [
        'title' => 'DC Ply Mens 2016/2017 Snowboard',
        'category' => 'Доски и лыжи',
        'price' => 159999,
        'image_url' => 'img/lot-2.jpg',
        'expire_date' => '2026-04-16',
    ],
    [
        'title' => 'Крепления Union Contact Pro 2015 года размер L/XL',
        'category' => 'Крепления',
        'price' => 8000,
        'image_url' => 'img/lot-3.jpg',
        'expire_date' => '2026-04-17',
    ],
    [
        'title' => 'Ботинки для сноуборда DC Mutiny Charcoal',
        'category' => 'Ботинки',
        'price' => 10999,
        'image_url' => 'img/lot-4.jpg',
        'expire_date' => '2026-04-18',
    ],
    [
        'title' => 'Куртка для сноуборда DC Mutiny Charcoal',
        'category' => 'Одежда',
        'price' => 7500,
        'image_url' => 'img/lot-5.jpg',
        'expire_date' => '2026-04-19',
    ],
    [
        'title' => 'Маска Oakley Canopy',
        'category' => 'Разное',
        'price' => 5400,
        'image_url' => 'img/lot-6.jpg',
        'expire_date' => '2026-04-20',
    ],
];
