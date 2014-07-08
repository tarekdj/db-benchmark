Database library benchmark based on Employees Sample Database

The task is:
> Select 500 employees from the Employees database, for each of them show all of their salaries and all the departments they belong to.

See [post by Tharos on Nette forum](http://forum.nette.org/cs/viewtopic.php?pid=106521#p106521) for original motivation.

Usage:
- load dump employees.sql
- update db user & password in run-*.php script.
- run `composer update` for each library
- run `testall` or reach library individually `php run-employees.php`

--------------------------------------
PHPLIST QUERY
--------------------------------------
run the run-phplist.php file for a phplist query
currently only implemented in PDO and partially in HARPORM


Queries to be executed:
_____________________________


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
	Config::getTableName('listuser'),
	Config::getTableName('user'),
	Config::getTableName('listmessage'),
	Config::getTableName('usermessage')
);

$userids_result = phpList::DB()->query($userids_query);
------------------------------------

$req = Sql_Query(sprintf('select list.name from %s as list,%s as listuser where list.id = listuser.listid and listuser.userid = %d',$GLOBALS["tables"]["list"],$GLOBALS["tables"]["listuser"],$userdata["id"]));

------------------------------------

