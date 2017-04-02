<?php

namespace AppBundle\Model;


use AppBundle\Entity\Device;

class Open extends AbstractState
{
    public function ready() : Device
    {
        $this->device->setState('ready');
        return $this->device;
    }


}