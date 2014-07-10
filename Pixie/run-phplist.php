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
    'password'  => $db_pass,
    'prefix'    => 'phplist_' //optional
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
    $query = $qb->table('user_user')
        ->join('listuser', null, null, null, 'CROSS')
        ->join('listmessage', null, null, null, 'CROSS')
        ->leftJoin('usermessage', function($table)
            {
                global $i;
                $table->on('usermessage.messageid', '=', $i);
                $table->on('usermessage.userid', '=', 'listuser.userid');
            })
        ->where('listmessage.messageid', '=', $i)
        ->where('listmessage.listid', '=', 'listuser.listid')
        ->where('user_user.id', '=', 'listuser.userid')
        ->where('usermessage.userid', 'IS')
        ->where('user_user.confirmed')
        ->where('!user_user.blacklisted')
        ->where('!user_user.disabled')
        ->select('user_user.id');
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
