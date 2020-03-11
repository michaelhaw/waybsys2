<?php

// src/DataFixtures/UserFixtures.php
namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use App\DataFixtures\EmployeeFixtures;
use App\Entity\User;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
	private $encoder;
	
	public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

	public function load(ObjectManager $manager)
    {
        $userAdmin = $this->getAdminUser($manager);
		
		$userAdmin->setUsername('admin');
		
		if(!$userAdmin->getPassword()){
			$password = $this->encoder->encodePassword($userAdmin, 'admin');
			
			$userAdmin->setPassword($password);
		}
		
		$adminRole[] = 'ROLE_SUPER_ADMIN';
		$userAdmin->setRoles($adminRole);
		$userAdmin->setEmployee($this->getReference(EmployeeFixtures::ADMIN_EMPLOYEE_REFERENCE));

        $manager->persist($userAdmin);
        $manager->flush();
    }
	
	private function getAdminUser(ObjectManager $manager){
		return $manager->getRepository(User::class)->findOneBy(array('username' => 'admin')) ?: new User();
	}
	
	public function getDependencies()
    {
        return array(
            EmployeeFixtures::class,
        );
    }
}