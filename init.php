<?php

declare(strict_types=1);

date_default_timezone_set('Europe/Moscow');

const BASE_PATH = __DIR__;

require_once BASE_PATH . '/util/const.php';
require_once BASE_PATH . '/enum/HttpMethodEnum.php';
require_once BASE_PATH . '/enum/HttpCodeEnum.php';
require_once BASE_PATH . '/functions/helpers.php';
require_once BASE_PATH . '/functions/functions.php';
require_once BASE_PATH . '/functions/database-core.php';
require_once BASE_PATH . '/functions/database.php';
require_once BASE_PATH . '/validation/rules.php';
require_once BASE_PATH . '/validation/validators.php';
require_once BASE_PATH . '/validation/index.php';
require_once BASE_PATH . '/functions/upload.php';
require_once BASE_PATH . '/functions/form.php';

$db_config = require BASE_PATH . '/config/db.php';

$db_connection = db_connect($db_config);

$is_auth = (bool) rand(0, 1);
$user = [
    'id'   => 1,
    'name' => 'Александр',
];

$categories = get_all_categories($db_connection);
