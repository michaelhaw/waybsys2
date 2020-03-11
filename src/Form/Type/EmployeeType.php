<?php

// src/Form/Type/EmployeeType.php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Entity\Employee;

class EmployeeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('first_name', TextType::class)
            ->add('last_name', TextType::class)
            ->add('extra_name', TextType::class, array('required' => false))
            ->add('middle_name', TextType::class, array('required' => false))
            ->add('email', TextType::class, array('required' => false))
			
            ->add('local_office', ChoiceType::class, array(
				'choices' => array(
					'Manila' => 'MNL',
					'Cebu' => 'CEB',
					'Iloilo' => 'ILO',
					'Bacolod' => 'BCD'
				),
				'required' => false
			))
			
            ->add('save', SubmitType::class, array('label' => 'Submit'))
        ;
    }
	
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => Employee::class,
		));
	}
}