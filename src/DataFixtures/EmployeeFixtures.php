<?php

// src/DataFixtures/EmployeeFixtures.php
namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

use App\Entity\Employee;

class EmployeeFixtures extends Fixture
{
	public const ADMIN_EMPLOYEE_REFERENCE = 'admin-employee';

    public function load(ObjectManager $manager)
    {
        $employeeAdmin = $this->getAdminEmployee($manager);

		$employeeAdmin->setFirstName('System');
		$employeeAdmin->setLastName('Admin');
		$employeeAdmin->setEmail('admin@waybsys.com');
		
        $manager->persist($employeeAdmin);
        $manager->flush();
		
		// other fixtures can get this object using the EmployeeFixtures::ADMIN_EMPLOYEE_REFERENCE constant
        $this->addReference(self::ADMIN_EMPLOYEE_REFERENCE, $employeeAdmin);
    }
	
	private function getAdminEmployee(ObjectManager $manager){
		return $manager->getRepository(Employee::class)->findOneBy(array('email' => 'admin@waybsys.com')) ?: new Employee();
	}
}