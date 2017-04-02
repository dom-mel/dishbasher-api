<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Device;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadDevices implements FixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $devices = [
            ['dishwasher', 'ready', 'super clean', 0],
            ['kitten-washer', 'running', 'kitten clean', 10],
            ['dishwasher-perfect-edition', 'open', 'perfect wash', 0],
            ['oven', 'running', 'hot like hell', 20],
        ];

        foreach ($devices as $device) {
            $deviceEntity = new Device();
            $deviceEntity->setName($device[0]);
            $deviceEntity->setState($device[1]);
            $deviceEntity->setProgram($device[2]);
            $deviceEntity->setRemaining($device[3]);
            $manager->persist($deviceEntity);
        }

        $manager->flush();
    }
}