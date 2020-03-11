<?php

// src/Controller/WaybillController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Doctrine\Common\Collections\ArrayCollection;

use App\Utility\Navigation\Navigation;

use App\Entity\Waybill;

use App\Form\Type\WaybillType;

class WaybillController extends AbstractController
{

	/**
     * @Route("/waybill/create", name="waybill_create")
     */
    public function createWaybill(Request $request)
    {
		$nav = new Navigation();
		
		//Get current employee
		$user = $this->getUser();
		$employee = $user->getEmployee();
		
		$waybill = new Waybill();
		$waybill->setReceivedBy(substr($employee->getFirstName(),0,8));
		$waybill->setReceivedAt($employee->getLocalOffice());
		$form = $this->createForm(WaybillType::class, $waybill);
		
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			
			$waybill = $form->getData();

			$em = $this->getDoctrine()->getManager();
			
			$em->persist($waybill);
			
			foreach($waybill->getCargo() as $cargo) {
				$cargo->setWaybill($waybill);
				$em->persist($cargo);
			}
			
			$em->flush();

			$this->addFlash(
				'success',
				'Waybill was created successfully!'
			);

			return $this->redirectToRoute('waybill_create');
		}
		
		return $this->render('waybsys/waybill/waybill-create.html.twig', [
            'navigation' => $nav->getNavigationDropdownArray(),
			'form' => $form->createView(),
        ]);
	}
	
	/**
     * @Route("/waybill/search", name="waybill_search")
     */
    public function searchWaybill(Request $request)
    {
		$nav = new Navigation();
		
		$defaultData = array('message' => 'Search Waybill',);
		$waybill_no = '';
		$shipper_id = '';
		$consignee_id = '';
		$destination = '';
		
		$form = $this->createFormBuilder($defaultData)
			->setMethod('GET')
			->add('waybill_no', TextType::class, array('required' => false))
            ->add('shipper', EntityType::class, array(
				'class' => 'App:Customer',
				'choice_label' => 'customer_name',
				'required' => false,
			))
            ->add('consignee', EntityType::class, array(
				'class' => 'App:Customer',
				'choice_label' => 'customer_name',
				'required' => false,
			))
            ->add('destination', ChoiceType::class, array(
				'choices' => array(
					'All' => '%',
					'Manila' => 'MNL',
					'Cebu' => 'CEB',
					'Iloilo' => 'ILO',
					'Bacolod' => 'BCD'
				),
			))
			->add('search', SubmitType::class, array('label' => 'Search'))
			->getForm();
			
		$form->handleRequest($request);
		
		if ($form->isSubmitted() && $form->isValid()) {
			$data = $form->getData();
			
			$waybill_no = $data['waybill_no'];
			if($data['shipper']) {
				$shipper_id = $data['shipper']->getCustomerId();
			}
			if($data['consignee']) {
				$consignee_id = $data['consignee']->getCustomerId();
			}
			$destination = $data['destination'];
		}
		
		$em = $this->getDoctrine()->getManager();
		$query = $em->createQuery(
						'SELECT w
						FROM App:Waybill w
						WHERE lower(w.waybill_no) like lower(:waybill_no)
						AND lower(w.destination) like lower(:destination)
						AND IDENTITY(w.shipper) like :shipper_id
						AND IDENTITY(w.consignee) like :consignee_id
						ORDER BY w.waybill_no ASC'
					)
						->setParameter('waybill_no','%'.$waybill_no.'%' )
						->setParameter('destination','%'.$destination.'%' )
						->setParameter('shipper_id',$shipper_id ?: '%' )
						->setParameter('consignee_id',$consignee_id ?: '%' );
		$waybills = $query->getResult();
		
		
		
		return $this->render('waybsys/waybill/waybill-search.html.twig', [
            'navigation' => $nav->getNavigationDropdownArray(),
			'form' => $form->createView(),
			'waybills' => $waybills,
        ]);
	}
	
	/**
     * @Route("/waybill/edit/{waybill_no}", name="waybill_edit")
     */
    public function editWaybill(Request $request, $waybill_no)
    {
		$nav = new Navigation();
		
		$waybill = $this->getDoctrine()
			->getRepository('App:Waybill')
			->findOneBy(array('waybill_no' => $waybill_no));
			
		$originalCargoList = new ArrayCollection();
		
		// Create copy of original list of included Cargo
		foreach($waybill->getCargo() as $cargo) {
			$originalCargoList->add($cargo);
		}

		$form = $this->createForm(WaybillType::class, $waybill);
		
		$form->handleRequest($request);
		
		if ($form->isSubmitted() && $form->isValid()) {
			
			$waybill = $form->getData();

			$em = $this->getDoctrine()->getManager();
			
			foreach($originalCargoList as $cargo) {
				if(false === $waybill->getCargo()->contains($cargo)) {
					$cargo->setWaybill(null);
					$em->remove($cargo);
				}
			}
			
			foreach($waybill->getCargo() as $cargo) {
				$cargo->setWaybill($waybill);
				$em->persist($cargo);
			}
			
			$em->flush();

			$this->addFlash(
				'success',
				'AR was modified successfully!'
			);

			return $this->redirectToRoute('waybill_edit', array('waybill_no' => $waybill_no));
		}
		
		return $this->render('waybsys/waybill/waybill-edit.html.twig', [
            'navigation' => $nav->getNavigationDropdownArray(),
			'form' => $form->createView(),
        ]);
	}
	
	/**
     * @Route("/print/waybill/{waybill_no}", name="print_waybill")
     */
    public function printWaybill(Request $request, $waybill_no)
    {
		$waybill = $this->getDoctrine()
			->getRepository('App:Waybill')
			->findOneBy(array('waybill_no' => $waybill_no));
			
		return $this->render('waybsys/print/print-waybill.html.twig', [
			'waybill' => $waybill,
        ]);
	}
}