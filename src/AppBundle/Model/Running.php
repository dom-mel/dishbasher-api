<?php

namespace AppBundle\Model;


use AppBundle\Entity\Device;

class Running extends AbstractState
{
    public function ready(): Device
    {
        $this->device->setRemaining(0);
        $this->device->setState('ready');
        return $this->device;
    }

}