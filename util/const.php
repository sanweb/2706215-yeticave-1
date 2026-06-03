<?php

declare(strict_types=1);

// DateTime
const SECONDS_PER_MINUTE = 60;
const SECONDS_PER_HOUR   = SECONDS_PER_MINUTE * 60;

// Database connection
const DEFAULT_MYSQL_PORT = 3306;

// URLs
const HOMEPAGE_URL  = '/';
const ERROR_404_URL = '/404.php';

// Lots
const LIMIT_RECENT_LOTS = 6;

// Uploads
const UPLOADS_URL = '/uploads';
const UPLOADS_DIR = BASE_PATH . UPLOADS_URL;
const MAX_UPLOADED_FILE_SIZE = 2 * 1024 * 1024; // 2MB
const ALLOWED_IMAGE_TYPES = [
    'jpg'  => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'png'  => 'image/png',
];

// Assets
const ASSET_TYPE_CSS = 'css';
const ASSET_TYPE_JS  = 'js';

// Forms
// TODO: Create enum?
const CREATE_LOT_FORM_KEY = 'create-lot-form';
const CREATE_BET_FORM_KEY = 'create-bet-form';
const CREATE_USER_FORM_KEY = 'create-user-form';
const LOGIN_USER_FORM_KEY = 'login-user-form';
