<?php

// src/Controller/UserSettingsController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use App\Utility\Navigation\Navigation;
use App\Entity\User;
	
/**
 * @IsGranted("ROLE_USER")
 */
class UserSettingsController extends AbstractController
{	
	/**
     * @Route("/settings/change/password", name="change_password")
     */
    public function changePassword(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $nav = new Navigation();

		$defaultData = array('message' => 'Change Password',);
		$form = $this->createFormBuilder($defaultData)
			->add('password', RepeatedType::class, array(
				'type' => PasswordType::class,
				'invalid_message' => 'The password fields must match.',
				'options' => array('attr' => array('class' => 'password-field')),
				'required' => true,
				'first_options'  => array('label' => 'New Password'),
				'second_options' => array('label' => 'Confirm New Password'),
				)
			)
			->add('save', SubmitType::class, array('label' => 'Submit'))
			->getForm();

		
		$form->handleRequest($request);
		
		if ($form->isSubmitted() && $form->isValid()) {
			
			//$user = $this->get('security.token_storage')->getToken()->getUser();
			$user = $this->getUser();
			
			$data = $form->getData();
			
			$plainpassword = $data['password'];
			
			//$encoder = $this->container->get('security.password_encoder');
			$password = $encoder->encodePassword($user, $plainpassword);
			
			$user->setPassword($password);

			$em = $this->getDoctrine()->getManager();
			$em->persist($user);
			$em->flush();
			
			$this->addFlash(
				'success',
				'Password was changed successfully!'
			);

			return $this->redirectToRoute('change_password');
		}
		
		return $this->render('waybsys/settings/change-password.html.twig', [
            'navigation' => $nav->getNavigationDropdownArray(),
			'form' => $form->createView(),
        ]);
    }
}