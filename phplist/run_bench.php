<?php


function init_bench_phpList() {
    include __DIR__ . '/helper/DefaultConfig.php';
    include __DIR__ . '/UserConfig.php';
    include __DIR__ . '/Config.php';
    include __DIR__ . '/helper/IDatabase.php';
    include __DIR__ . '/helper/MySQLi.php';
    include __DIR__ . '/phpList.php';
}

function start_bench_phpList() {
    //first query
    for($i = 0; $i<5; $i++){
        $userids_query = sprintf(
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
        );

        \phpList\phpList::DB()->query($userids_query);
    }

    //second query
    for($i=100; $i<500; $i++){
        $result = \phpList\phpList::DB()->query(
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
}