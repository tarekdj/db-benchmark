<?php

require __DIR__ . '/../print_benchmark_result.php';
require __DIR__ . '/../db.php';

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

foreach ($pdo->query("SELECT * FROM employees LIMIT 0 , $limit ") as $employee) {
	echo "$employee[first_name] $employee[last_name] ($employee[emp_no])\n";
	print "Salaries:\n";
	$salaries = $pdo->query("SELECT salary FROM salaries WHERE emp_no = '$employee[emp_no]'")->fetchAll();
	foreach($salaries as $salary)
		echo $salary["salary"] . "\n";
	echo "Departments:\n";
	$depts = $pdo->query("SELECT departments.dept_name FROM dept_emp INNER JOIN departments on (departments.dept_no=dept_emp.dept_no)  WHERE dept_emp.emp_no = $employee[emp_no]")->fetchAll();
	foreach($depts as $dept)
		echo $dept['dept_name'] . "\n";	
}

ob_end_clean();

print_benchmark_result('PDO');
