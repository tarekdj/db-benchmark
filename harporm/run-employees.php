<?php
use Harp\Query\DB;
require __DIR__ . '/../print_benchmark_result.php';
require __DIR__ . '/../db.php';

if (@!include __DIR__ . '/vendor/autoload.php') {
    echo 'Install Fluent using `composer install`'. PHP_EOL;
    exit(1);
}

date_default_timezone_set('Europe/Prague');


$limit = 500;



DB::setConfig([
    'dsn' => $db_location.';host=127.0.0.1',
    'username' => $db_user,
    'password' => $db_user,
]);



$time = -microtime(TRUE);
ob_start();


$query = DB::select()
    ->from('employees')
    ->limit($limit);

foreach ($query->execute() as $row) {
    echo "$row[first_name] $row[last_name] ($row[emp_no])\n";
	echo "Salaries:\n";
	$salaries = DB::select()
		->from('salaries')
		->where('emp_no', $row['emp_no']);
	foreach ($salaries->execute() as $salary) {
		echo $salary["salary"] . "\n";
	}
	echo "Departments:\n";

$depts = DB::select()
    ->from('dept_emp')
    ->where('emp_no', $row['emp_no'])
    ->join('departments', ['departments.dept_no' => 'dept_emp.dept_no']);
	foreach($depts->execute() as $dept)
		echo $dept['dept_name'] . "\n";	
}


ob_end_clean();

print_benchmark_result('Harp-orm');
