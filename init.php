<?php

declare(strict_types=1);

date_default_timezone_set('Europe/Moscow');

const BASE_PATH = __DIR__;

require_once BASE_PATH . '/config/const.php';
require_once BASE_PATH . '/enum/HttpMethodEnum.php';
require_once BASE_PATH . '/enum/HttpCodeEnum.php';
require_once BASE_PATH . '/functions/helpers.php';
require_once BASE_PATH . '/functions/common.php';
require_once BASE_PATH . '/functions/database/index.php';
require_once BASE_PATH . '/validation/index.php';
require_once BASE_PATH . '/functions/upload.php';
require_once BASE_PATH . '/functions/form.php';
require_once BASE_PATH . '/functions/auth.php';

$db_config = require BASE_PATH . '/config/db.php';

$db_connection = db_connect($db_config);

session_start();

$user = get_auth_user();

$categories = get_all_categories($db_connection);
