<?php

namespace App\Repository;

use App\Entity\Employee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Employee|null find($id, $lockMode = null, $lockVersion = null)
 * @method Employee|null findOneBy(array $criteria, array $orderBy = null)
 * @method Employee[]    findAll()
 * @method Employee[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmployeeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Employee::class);
    }
	
	/**
     * @return Employee[]
     */
    public function findAllEmployeeWithNoUser(): array
    {
        $entityManager = $this->getEntityManager();
		
		$queryEmployeesWithUser = $this->createQueryBuilder('e')
			->select('IDENTITY(u.employee)')
			->join('App:User', 'u', 'WITH', 'u.employee = e.employee_id');

		$queryEmployeesWithoutUser = $this->createQueryBuilder('eu');
		$queryEmployeesWithoutUser->where($queryEmployeesWithoutUser->expr()->notIn('eu.employee_id', $queryEmployeesWithUser->getDQL()));

		$employeesWithoutUser = $queryEmployeesWithoutUser->getQuery()->getResult();

        // returns an array of Employee objects
        return $employeesWithoutUser;
    }

    // /**
    //  * @return Employee[] Returns an array of Employee objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Employee
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}