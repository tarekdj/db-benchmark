<?php

use Model\Mapper;
use Model\Repository\EmployeesRepository;

require_once __DIR__ . '/vendor/dibi.min.php';
require_once __DIR__ . '/vendor/LeanMapper/loader.php';
require_once __DIR__ . '/Model/Mapper.php';
require_once __DIR__ . '/Model/Entity/Employee.php';
require_once __DIR__ . '/Model/Entity/Salary.php';
require_once __DIR__ . '/Model/Entity/Department.php';
require_once __DIR__ . '/Model/Repository/EmployeesRepository.php';

date_default_timezone_set('Europe/Prague');

$limit = 500;

$connection = new DibiConnection(array(
	'username' => 'root',
	'password' => '',
	'database' => 'employees',
));


$time = -microtime(TRUE);
ob_start();

$employeesRepository = new EmployeesRepository($connection, new Mapper);

foreach ($employeesRepository->findAll($limit) as $employee) {
	echo "$employee->firstName $employee->lastName ($employee->empNo)\n";
	echo "Salaries:\n";
	foreach ($employee->salaries as $salary) {
		echo $salary->salary, "\n";
	}
	echo "Departments:\n";
	foreach ($employee->departments as $department) {
		echo $department->deptName, "\n";
	}
}

ob_end_clean();

echo 'Time: ', sprintf('%0.3f', $time + microtime(TRUE)), ' s | ',
	'Memory: ', (memory_get_peak_usage() >> 20), ' MB | ',
	'PHP: ', PHP_VERSION;
