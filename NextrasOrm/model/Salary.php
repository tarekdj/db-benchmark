<?php

namespace model;

use DateTime;
use Nextras\Orm\Entity\Entity;
use Nextras\Orm\Relationships\ManyHasOne;


/**
 * @property ManyHasOne|Employee $employee {primary} {m:1 EmployeesRepository}
 * @property DateTime $fromDate {primary}
 * @property DateTime $toDate
 * @property int $salary
 */
class Salary extends Entity
{
}
