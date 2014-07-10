<?php
require __DIR__ . '/../print_benchmark_result.php';
require __DIR__ . '/../db.php';

include __DIR__ . '/../phplist/helper/DefaultConfig.php';
include __DIR__ . '/../phplist/UserConfig.php';
include __DIR__ . '/../phplist/Config.php';

if (@!include __DIR__ . '/vendor/autoload.php') {
    echo 'Install Pixie using `composer install`'. PHP_EOL;
    exit(1);
}

date_default_timezone_set('Europe/Prague');

$limit = 500;

$config = array(
    'driver'    => $db_driver, // Db driver
    'host'      => $db_host,
    'database'  => $db_name,
    'username'  => $db_user,
    'password'  => $db_pass/*,
    'prefix'    => 'phplist_' //optional*/
);

$connection = new \Pixie\Connection('mysql', $config);
$qb = new \Pixie\QueryBuilder\QueryBuilderHandler($connection);

$time = -microtime(TRUE);
ob_start();


$lu = \phpList\Config::getTableName('listuser');
$u = \phpList\Config::getTableName('user');
$lm = \phpList\Config::getTableName('listmessage');
$um = \phpList\Config::getTableName('usermessage');
$l = \phpList\Config::getTableName('list');

for($i = 0; $i<5; $i++){
    $query = $qb->table($lu)
        ->join($u, "{$lu}.userid", "=", "{$u}.id", 'CROSS')
        ->join($lm, "{$lm}.listid", '=', "{$lu}.listid", 'CROSS')
        ->leftJoin($um, function($table)
            {
                global $i, $um, $lu, $qb;
                $table->on("{$um}.messageid", '=',  $qb->raw($i));
                $table->on("{$um}.userid", '=', "{$lu}.userid");
            })
        ->where("{$lm}.messageid", '=', $i)
        ->where("{$um}.userid", 'IS', null)
        ->where("{$u}.confirmed", '=', 1)
        ->where("{$u}.blacklisted", '!=', 1)
        ->where("{$u}.disabled", '!=', 1)
        ->select("{$u}.id");
    //TODO: distinct query
    echo $query->getQuery()->getRawSql();exit();

}

for($i=100; $i<2000; $i++){
    /*$query = DB::select()
        ->from($lu)
        ->join($l, array("{$lu}.listid" => "{$l}.id"), 'INNER')
        ->where("{$lu}.userid", $i);
    $query->execute();*/
}



ob_end_clean();

print_benchmark_result('Harp-orm');
