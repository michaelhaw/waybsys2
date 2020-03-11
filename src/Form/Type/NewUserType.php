<?php

// src/Form/Type/NewUserType.php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

use App\Entity\Employee;
use App\Entity\User;

class NewUserType extends AbstractType
{
	private $entityManager;
	private $logger;

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
		$this->logger = $logger;
    }
	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('employee', EntityType::class, [
				'class' => Employee::class,
				'choices' => $this->entityManager->getRepository(Employee::class)->findAllEmployeeWithNoUser(),
				'choice_label' => function ($employee) {
					return $employee->getLastName() . ', ' . $employee->getFirstName();
				},
				'required' => true
			])
            ->add('username', TextType::class)
            ->add('password', PasswordType::class)
            ->add('save', SubmitType::class, array('label' => 'Submit'))
        ;
    }
	
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => User::class,
		));
	}
	
	private function getEmployeesWithNoUser() 
	{	
		$queryBuilder  = $this->entityManager->createQueryBuilder();

		$users = $queryBuilder
			->select('u')
			->from('App:User', 'u')
			->where($queryBuilder->expr()->eq(1,1))
			->getQuery()
			->getResult();

		$employeesWNU = $queryBuilder
			->select('e')
			->from('App:Employee', 'e')
			->where($queryBuilder->expr()->notIn('e.employee_id',  $users))
			->getQuery()
			->getResult();
	
		return $employeesWNU;
	}
}