<?php

require __DIR__ . '/../print_benchmark_result.php';
require __DIR__ . '/../db.php';

include __DIR__ . '/../phplist/helper/DefaultConfig.php';
include __DIR__ . '/../phplist/UserConfig.php';
include __DIR__ . '/../phplist/Config.php';

date_default_timezone_set('Europe/Prague');

$useCache = TRUE;
$limit = 500;

$pdo = new PDO(
	$db_location,
	$db_user,
	$db_pass
);

$time = -microtime(TRUE);
ob_start();

$pdo->Query('reset query cache');


