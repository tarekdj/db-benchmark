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

$config = array(
            'driver'    => 'mysql', // Db driver
            'host'      => 'localhost',
            'database'  => 'employees',
            'username'  => 'root',
            'password'  => 'root',
        );

new \Pixie\Connection('mysql', $config, 'QB');



$time = -microtime(TRUE);
ob_start();
$query = QB::table('employees')->limit($limit);
	foreach ($query->get() as $row) {
	echo "$row->first_name $row->last_name ($row->emp_no)\n";
		echo "Salaries:\n";	
		$salaries = QB::table('salaries')
			->where('emp_no','=', $row->emp_no);
		foreach ($salaries->get() as $salary) {
			echo $salary->salary . "\n";
		}	
		echo "Departments:\n";
		$depts = QB::table('dept_emp')
			->where('dept_emp.emp_no','=', $row->emp_no)
			->join('departments', 'departments.dept_no' ,'=', 'dept_emp.dept_no');
		foreach($depts->get() as $dept)
			echo $dept->dept_name . "\n";	
	}	
	
ob_end_clean();

print_benchmark_result('Pixie');
