<?php

namespace App\DataFixtures;

use App\Domain\Localization\Entity\City;
use App\Domain\Localization\Entity\Country;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LocalizationFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($i=1;$i<50;$i++){
            $country = new Country();
            $country->setCurrency("$");
            $country->setIsActive(true);
            $country->setIsoThree("iso3-".$i);
            $country->setIsoTwo("iso2-".$i);
            $country->setName("country-".$i);
            $manager->persist($country);
            for($j=1;$j<20;$j++){
                $city = new City();
                $city->setCountry($country);
                $city->setIsActive(true);
                $city->setName('city-'.$j.'-'.$country->getName());
                $manager->persist($city);
            }
        }
        

        $manager->flush();
    }
}
