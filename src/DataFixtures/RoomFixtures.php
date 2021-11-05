<?php

namespace App\DataFixtures;

use App\Entity\Room;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RoomFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $room = new Room();
        $room
            ->setName('Salle à manger')
        ;
        $manager->persist($room);

        $room = new Room();
        $room
            ->setName('Salon')
        ;
        $manager->persist($room);

        $room = new Room();
        $room
            ->setName('Cuisine')
        ;
        $manager->persist($room);


        $room = new Room();
        $room
            ->setName('Salle de bain')
        ;
        $manager->persist($room);

        $room = new Room();
        $room
            ->setName('Couloir RDC')
        ;
        $manager->persist($room);


        $room = new Room();
        $room
            ->setName('Toilette RDC')
        ;
        $manager->persist($room);

        $room = new Room();
        $room
            ->setName('Veranda')
        ;
        $manager->persist($room);


        $room = new Room();
        $room
            ->setName('Chambre Gen & Alex')
        ;
        $manager->persist($room);


        $room = new Room();
        $room
            ->setName('Chambre Godric')
        ;
        $manager->persist($room);


        $room = new Room();
        $room
            ->setName('Atelier Gen')
        ;
        $manager->persist($room);


        $room = new Room();
        $room
            ->setName('Bureau')
        ;
        $manager->persist($room);

        $manager->flush();
    }
}
