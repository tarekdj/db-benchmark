<?php


require __DIR__ . '/../print_benchmark_result.php';
require __DIR__ . '/../db.php';

if (@!include __DIR__ . '/vendor/autoload.php') {
    echo 'Install Nette using `composer install`';
    exit(1);
}

$useCache = TRUE;

date_default_timezone_set('Europe/Prague');

$connection = new Nette\Database\Connection(
	$db_location,
	$db_user,
  $db_pass
);

$cacheStorage = new Nette\Caching\Storages\FileStorage(__DIR__ . '/temp');
$connection->setCacheStorage($useCache ? $cacheStorage : NULL);
$connection->setDatabaseReflection(new Nette\Database\Reflection\DiscoveredReflection($useCache ? $cacheStorage : NULL));
$dao = $connection;


$time = -microtime(TRUE);
ob_start();

foreach ($dao->table('employees')->limit(500) as $employe) {
	echo "$employe->first_name $employe->last_name ($employe->emp_no)\n";
	echo "Salaries:\n";
	foreach ($employe->related('salaries') as $salary) {
		echo $salary->salary, "\n";
	}
	echo "Departments:\n";
	foreach ($employe->related('dept_emp') as $department) {
		echo $department->dept->dept_name, "\n";
	}
}

ob_end_clean();

print_benchmark_result('NDB 2.0', 'Nette: ' . Nette\Framework::VERSION);
