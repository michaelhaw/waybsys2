<?php

// src/Twig/WaybsysExtension.php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

//use Symfony\Component\Intl\Intl;
use Symfony\Component\Intl\Countries;

class WaybsysExtension extends AbstractExtension
{
    public function getFilters()
    {
        return array(
            new TwigFilter('country', array($this, 'countryFilter')),
        );
    }

    public function countryFilter($countryCode, $locale = 'en')
    {
		\Locale::setDefault($locale);
		
		//$countries = Intl::getRegionBundle()->getCountryNames($locale);
        //$country = Intl::getRegionBundle()->getCountryName($countryCode, $locale);
		$countries = Countries::getNames();
		$country = Countries::getName($countryCode);

		return array_key_exists($countryCode, $countries)
			? $countries[$countryCode]
			: $countryCode;
    }
	

	public function getName()
	{
		return 'waybsys_extension';
	}
}