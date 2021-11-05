<?php

namespace App\DataFixtures;

use App\Entity\Vendor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class VendorFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $vendor = new Vendor();
        $vendor
            ->setName('Sonoff')
            ->setPath('sonoff.png')
            ->setWebsite('https://sonoff.tech/');
        $manager->persist($vendor);

        $vendor = new Vendor();
        $vendor
            ->setName('Danfoss')
            ->setPath('danfoss.png')
            ->setWebsite('https://www.danfoss.com/');
        $manager->persist($vendor);

        $vendor = new Vendor();
        $vendor
            ->setName('Nodon')
            ->setPath('nodon.jpg')
            ->setWebsite('https://nodon.fr/');
        $manager->persist($vendor);

        $manager->flush();
    }
}
