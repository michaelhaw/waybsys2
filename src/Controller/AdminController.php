<?php

// src/Controller/AdminController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use App\Utility\Navigation\Navigation;

use App\Entity\Employee;
use App\Entity\User;

use App\Form\Type\EmployeeType;
use App\Form\Type\NewUserType;

/**
 * @IsGranted("ROLE_ADMIN")
 */
class AdminController extends AbstractController
{
	/**
     * @Route("/admin")
     */
    public function adminAction()
    {
        return new Response('<html><body>Admin page!</body></html>');
    }
	
	/**
     * @Route("/admin/create/employee", name="admin_create_employee")
     */
    public function createEmployee(Request $request)
    {
		$nav = new Navigation();
		
		$newEmployee = new Employee();
		
		$form = $this->createForm(EmployeeType::class, $newEmployee);
		
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			
			$newEmployee = $form->getData();

			$em = $this->getDoctrine()->getManager();
			$em->persist($newEmployee);
			$em->flush();

			$this->addFlash(
				'success',
				'Employee was created successfully!'
			);

			return $this->redirectToRoute('admin_create_employee');
		}
		
		return $this->render('waybsys/admin/admin-create-employee.html.twig', [
            'navigation' => $nav->getNavigationDropdownArray(),
			'form' => $form->createView(),
        ]);
    }
	
	/**
     * @Route("/admin/create/user", name="admin_create_user")
     */
    public function createUser(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $nav = new Navigation();
		
		$newUser = new User();
		
		$form = $this->createForm(NewUserType::class, $newUser);
		
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			
			$newUser = $form->getData();
			
			$password = $encoder->encodePassword($newUser, $newUser->getPassword());
			
			$newUser->setPassword($password);
			
			$userRole[] = 'ROLE_USER';
			$newUser->setRoles($userRole);

			$em = $this->getDoctrine()->getManager();
			$em->persist($newUser);
			$em->flush();

			$this->addFlash(
				'success',
				'User was created successfully!'
			);

			return $this->redirectToRoute('admin_create_user');
		}
		
		return $this->render('waybsys/admin/admin-create-user.html.twig', [
            'navigation' => $nav->getNavigationDropdownArray(),
			'form' => $form->createView(),
        ]);
		//return new Response('<html><body>Create User!</body></html>');
    }
}