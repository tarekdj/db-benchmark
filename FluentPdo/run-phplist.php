<?php

require __DIR__ . '/../print_benchmark_result.php';
require __DIR__ . '/../db.php';

include __DIR__ . '/../phplist/helper/DefaultConfig.php';
include __DIR__ . '/../phplist/UserConfig.php';
include __DIR__ . '/../phplist/Config.php';

if (@!include __DIR__ . '/vendor/autoload.php') {
    echo 'Install Fluent using `composer install`'. PHP_EOL;
    exit(1);
}

date_default_timezone_set('Europe/Prague');

$useCache = TRUE;
$limit = 500;

$pdo = new PDO(
	$db_location,
	$db_user,
	$db_pass
);

$fpdo = new FluentPDO($pdo);

$lu = \phpList\Config::getTableName('listuser');
$u = \phpList\Config::getTableName('user');
$lm = \phpList\Config::getTableName('listmessage');
$um = \phpList\Config::getTableName('usermessage');
$l = \phpList\Config::getTableName('list');


$time = -microtime(TRUE);
ob_start();

for ($i = 0; $i<5; $i++) {

	$users = $fpdo
    ->from($lu.' AS listuser')
    ->leftJoin($u .' AS u')
    ->lectJoin($lm.' AS listmessage')
    ->leftJoin($um.' AS um ON (um.messageid = '.$i.' AND um.userid = listuser.userid)')
    ->select('u.id')->where('true AND listmessage.messageid = '.$i.'
                  AND listmessage.listid = listuser.listid
                  AND u.id = listuser.userid
                  AND um.userid IS NULL
                  AND u.confirmed and !u.blacklisted and !u.disabled');
}

for($i=100; $i<2000; $i++) {
    $result = $fpdo->from($lu. ' as listuser')
      ->innerJoin($l.' as l')
      ->where('lu.userid = '.$i);
}


ob_end_clean();

print_benchmark_result('FluentPDO');
