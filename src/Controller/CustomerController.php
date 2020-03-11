<?php

// src/Controller/CustomerController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use App\Utility\Navigation\Navigation;

use App\Entity\Customer;

use App\Form\Type\CustomerType;

/**
 * @IsGranted("ROLE_ENCODER")
 */
class CustomerController extends AbstractController
{
	/**
     * @Route("/customer/create", name="customer_create")
     */
    public function createCustomer(Request $request)
    {
		$nav = new Navigation();
		
		$customer = new Customer();
		$customer->setCustomerCountry('PH');
		
		$form = $this->createForm(CustomerType::class, $customer);
		
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			
			$customer = $form->getData();

			$em = $this->getDoctrine()->getManager();
			$em->persist($customer);
			$em->flush();

			$this->addFlash(
				'success',
				'Customer was created successfully!'
			);

			return $this->redirectToRoute('customer_create');
		}
		
		return $this->render('waybsys/customer/customer-create.html.twig', [
            'navigation' => $nav->getNavigationDropdownArray(),
			'form' => $form->createView(),
        ]);
    }

	/**
     * @Route("/customer/search", name="customer_search")
     */
    public function searchCustomer(Request $request)
    {
		$nav = new Navigation();
		
		$defaultData = array('message' => 'Search Customer',);
		$customer_name = '';
		
		$form = $this->createFormBuilder($defaultData)
			->setMethod('GET')
			->add('customer_name', TextType::class, array('required' => False))
			->add('search', SubmitType::class, array('label' => 'Search'))
			->getForm();
			
		$form->handleRequest($request);
		
		if ($form->isSubmitted() && $form->isValid()) {
			$data = $form->getData();
			$customer_name = $data['customer_name'];
		}
		
		$em = $this->getDoctrine()->getManager();
		$query = $em->createQuery(
						'SELECT c
						FROM App:Customer c
						WHERE lower(c.customer_name) like lower(:customer_name)
						ORDER BY c.customer_name ASC'
						)->setParameter('customer_name','%'.$customer_name.'%' );
		$customers = $query->getResult();
		
		return $this->render('waybsys/customer/customer-search.html.twig', [
            'navigation' => $nav->getNavigationDropdownArray(),
			'form' => $form->createView(),
			'customers' => $customers,
        ]);
	}
	
	/**
     * @Route("/customer/edit/{customer_id}", name="customer_edit")
     */
    public function editCustomer(Request $request, $customer_id)
    {
		$nav = new Navigation();
		
		$customer = $this->getDoctrine()
			->getRepository('App:Customer')
			->find($customer_id);

		$form = $this->createForm(CustomerType::class, $customer);
		
		$form->handleRequest($request);
		
		if ($form->isSubmitted() && $form->isValid()) {
			
			$customer = $form->getData();

			$em = $this->getDoctrine()->getManager();
			
			$em->flush();

			$this->addFlash(
				'success',
				'Customer was modified successfully!'
			);

			return $this->redirectToRoute('customer_edit', array('customer_id' => $customer_id));
		}
		
		return $this->render('waybsys/customer/customer-create.html.twig', [
            'navigation' => $nav->getNavigationDropdownArray(),
			'form' => $form->createView(),
        ]);
	}

}