<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Device;
use AppBundle\Model\DeviceState;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadDevices implements FixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $devices = [
            ['dishwasher', DeviceState::STATE_READY, 'super clean', 0, false],
            ['kitten-washer', DeviceState::STATE_RUNNING, 'kitten clean', 10, false],
            ['dishwasher-perfect-edition', DeviceState::STATE_READY, 'perfect wash', 0, true],
            ['oven', DeviceState::STATE_RUNNING, 'hot like hell', 20, false],
        ];

        foreach ($devices as $device) {
            $deviceEntity = new Device();
            $deviceEntity->setName($device[0]);
            $deviceEntity->setState($device[1]);
            $deviceEntity->setProgram($device[2]);
            $deviceEntity->setFinishesAt($device[3]);
            $deviceEntity->setDoorOpen($device[4]);
            $manager->persist($deviceEntity);
        }

        $manager->flush();
    }
}