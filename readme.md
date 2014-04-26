Database library benchmark based on Employees Sample Database

The task is:
> Select 500 employees from the Employees database, for each of them show all of their salaries and all the departments they belong to.

See [post by Tharos on Nette forum](http://forum.nette.org/cs/viewtopic.php?pid=106521#p106521) for original motivation.

Usage:
- load dump employees.sql
- update db user & password in run-*.php script.
- run `composer update` for each library
- run `testall` or reach library individually `php run-employees.php`
