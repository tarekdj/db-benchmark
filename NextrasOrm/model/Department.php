<?php

namespace model;

use Nextras\Orm\Entity\Entity;
use Nextras\Orm\Relationships\OneHasMany;


/**
 * @property string $name
 * @property OneHasMany|Employee[] $employees {m:n EmployeesRepository $department}
 */
class Department extends Entity
{
}
