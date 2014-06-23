<?php

require __DIR__ . '/../print_benchmark_result.php';
require __DIR__ . '/../db.php';

if (@!include __DIR__ . '/vendor/autoload.php') {
    echo 'Install Fluent using `composer install`'. PHP_EOL;
    exit(1);
}

date_default_timezone_set('Europe/Prague');

$useCache = TRUE;
$limit = 500;

$pdo = new PDO(
	$db_location,
	$db_user,
	$db_pass
);

$fpdo = new FluentPDO($pdo);
$fpdo = new FluentPDO($pdo);

$time = -microtime(TRUE);
ob_start();

$employees = $fpdo->from('employees')
            ->limit($limit);
foreach ($employees as $employee) {
    echo "$employee[first_name] $employee[last_name] ($employee[emp_no])\n";
	echo "Salaries:\n";
	$salaries = $fpdo->from('salaries')
					 ->where('emp_no', $employee['emp_no']);
	foreach ($salaries as $salary) {
		echo $salary["salary"] . "\n";
	}
	echo "Departments:\n";
	$depts = $fpdo->from('dept_emp')->leftJoin('departments on departments.dept_no=dept_emp.dept_no')->select('departments.dept_name')->where('dept_emp.emp_no', $employee['emp_no']);
	foreach($depts as $dept)
		echo $dept['dept_name'] . "\n";	
}

ob_end_clean();

print_benchmark_result('FluentPDO');
