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

$lu = \phpList\Config::getTableName('listuser');
$u = \phpList\Config::getTableName('user');
$lm = \phpList\Config::getTableName('listmessage');
$um = \phpList\Config::getTableName('usermessage');
$l = \phpList\Config::getTableName('list');

$pdo->Query('reset query cache');

$time = -microtime(TRUE);
ob_start();

for($i = 0; $i<5; $i++){
	$pdo->query(sprintf(
			'SELECT DISTINCT u.id FROM %s AS listuser
                    CROSS JOIN %s AS u
                    CROSS JOIN %s AS listmessage
                    LEFT JOIN %s AS um
                      ON (um.messageid = %d AND um.userid = listuser.userid)
                WHERE true
                  AND listmessage.messageid = %d
                  AND listmessage.listid = listuser.listid
                  AND u.id = listuser.userid
                  AND um.userid IS NULL
                  AND u.confirmed and !u.blacklisted and !u.disabled',
            $lu,
            $u,
            $lm,
            $um,
            $i,
            $i
		));
}

for($i=100; $i<2000; $i++) {
    $result = $pdo->query(
      sprintf(
          'SELECT l.*
          FROM %s AS lu
              INNER JOIN %s AS l
              ON lu.listid = l.id
          WHERE lu.userid = %d',
          $lu,
          $l,
          $i
      )
    );
}


ob_end_clean();

print_benchmark_result('PDO');
