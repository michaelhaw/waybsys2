<?php

// src/Controller/DefaultController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use App\Utility\Navigation\Navigation;
use App\Entity\User;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
		/*
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ]);
		*/
		$nav = new Navigation();

		$user = $this->getUser();
		
		/*
		$this->addFlash(
			'notice',
			'Welcome, '. $user->getUsername() .'!'
		);
		*/
		
		return $this->render('waybsys/base.html.twig', [
            'navigation' => $nav->getNavigationDropdownArray(),
        ]);
    }
	
	/**
     * @Route("/registerAdmin")
	 * @IsGranted("ROLE_ADMIN")
     */
    public function registerAdmin()
    {
		
		$user = new User();
		$user->setUsername('new.admin');
		/*
		$encoder = $this->container->get('security.password_encoder');
		$encoded = $encoder->encodePassword($user, 'test1234');
		
		$user->setPassword($encoded);
		$user->setIsActive(true);
	
		$employee = $this->getDoctrine()
			->getRepository('AppBundle:Employee')
			->find(1);
	
		$user->setEmployee($employee);
		
		$em = $this->getDoctrine()->getManager();
		$em->persist($user);
		$em->flush();
		*/
        return new Response('<html><body>Created '. $user->getUsername() .'.</body></html>');
    }
}
