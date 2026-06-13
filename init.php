<?php

/** @noinspection PhpDefineCanBeReplacedWithConstInspection */

declare(strict_types=1);

date_default_timezone_set('Europe/Moscow');

define('BASE_PATH', __DIR__);
define('BASE_URL', getenv('APP_URL') ?? 'http://localhost:8080');

require_once BASE_PATH . '/config/const.php';
require_once BASE_PATH . '/enum/HttpMethodEnum.php';
require_once BASE_PATH . '/enum/HttpCodeEnum.php';
require_once BASE_PATH . '/functions/index.php';
require_once BASE_PATH . '/validation/index.php';

$db_config = require BASE_PATH . '/config/db.php';
$db_connection = db_connect($db_config);

session_start();

$user = get_auth_user();
$categories = get_all_categories($db_connection);
