<?php
use Harp\Query\DB;

require __DIR__ . '/../print_benchmark_result.php';
require __DIR__ . '/../db.php';

include __DIR__ . '/../phplist/helper/DefaultConfig.php';
include __DIR__ . '/../phplist/UserConfig.php';
include __DIR__ . '/../phplist/Config.php';

if (@!include __DIR__ . '/vendor/autoload.php') {
    echo 'Install Harprom using `composer install`'. PHP_EOL;
    exit(1);
}

date_default_timezone_set('Europe/Prague');

$limit = 500;

DB::setConfig([
    'dsn' => $db_location.';host='.$db_host,
    'username' => $db_user,
    'password' => $db_pass,
]);

$time = -microtime(TRUE);
ob_start();

$lu = \phpList\Config::getTableName('listuser');
$u = \phpList\Config::getTableName('user');
$lm = \phpList\Config::getTableName('listmessage');
$um = \phpList\Config::getTableName('usermessage');
$l = \phpList\Config::getTableName('list');

for($i = 0; $i<5; $i++){
	$query = DB::select()
		->from($lu)
        ->join($u, array("{$u}.id" => "{$lu}.userid"), 'CROSS')
        ->where(true, true)
        ->where("{$lm}.messageid", $i)
        ->where("{$lm}.listid", "{$lu}.listid" )
        ->where("{$u}.id", "{$lu}.userid" )
        ->type('DISTINCT');
    echo $query->sql();
    #exit();
    $query->execute();
}

for($i=100; $i<2000; $i++){
    $query = DB::select()
        ->from($lu)
        ->join($l, array("{$lu}.listid" => "{$l}.id"), 'INNER')
        ->where("{$lu}.userid", $i);
    $query->execute();
}



ob_end_clean();

print_benchmark_result('Harp-orm');
