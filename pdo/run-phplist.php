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
            \phpList\Config::getTableName('listuser'),
            \phpList\Config::getTableName('user_user'),
            \phpList\Config::getTableName('listmessage'),
            \phpList\Config::getTableName('usermessage'),
            $i,
            $i
		));
}

for($i=100; $i<2000; $i++){
        $result = $pdo->query(
            sprintf(
                'SELECT l.*
                FROM %s AS lu
                    INNER JOIN %s AS l
                    ON lu.listid = l.id
                WHERE lu.userid = %d',
                \phpList\Config::getTableName('listuser'),
                \phpList\Config::getTableName('list'),
                $i
            )
        );
    }


ob_end_clean();

print_benchmark_result('PDO');
