<?php

// src/Entity/Employee.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EmployeeRepository")
 * @ORM\Table(name="employees")
 * @UniqueEntity(fields="email", message="Email is already being used.")
 */
class Employee
{
	/**
     * @ORM\Id
     * @ORM\Column(name="employee_id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $employee_id;
	
	/**
     * @ORM\Column(name="last_name", type="string", length=50)
     */
    private $last_name;
	
	/**
     * @ORM\Column(name="first_name", type="string", length=50)
     */
    private $first_name;
	
	/**
     * @ORM\Column(name="extra_name", type="string", length=50, nullable=true)
     */
    private $extra_name;
	
	/**
     * @ORM\Column(name="middle_name", type="string", length=50, nullable=true)
     */
    private $middle_name;

    /**
     * @ORM\Column(name="email", type="string", length=60, unique=true)
	 * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email."
	 * )
     */
    private $email;
	
	/**
     * @ORM\Column(name="local_office", type="string", length=3, nullable=true)
     */
    private $local_office;


    /**
     * Get employeeId
     *
     * @return integer
     */
    public function getEmployeeId()
    {
        return $this->employee_id;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Employee
     */
    public function setLastName($lastName)
    {
        $this->last_name = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Employee
     */
    public function setFirstName($firstName)
    {
        $this->first_name = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * Set extraName
     *
     * @param string $extraName
     *
     * @return Employee
     */
    public function setExtraName($extraName)
    {
        $this->extra_name = $extraName;

        return $this;
    }

    /**
     * Get extraName
     *
     * @return string
     */
    public function getExtraName()
    {
        return $this->extra_name;
    }

    /**
     * Set middleName
     *
     * @param string $middleName
     *
     * @return Employee
     */
    public function setMiddleName($middleName)
    {
        $this->middle_name = $middleName;

        return $this;
    }

    /**
     * Get middleName
     *
     * @return string
     */
    public function getMiddleName()
    {
        return $this->middle_name;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Employee
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set localOffice
     *
     * @param string $localOffice
     *
     * @return Employee
     */
    public function setLocalOffice($localOffice)
    {
        $this->local_office = $localOffice;

        return $this;
    }

    /**
     * Get localOffice
     *
     * @return string
     */
    public function getLocalOffice()
    {
        return $this->local_office;
    }
}
