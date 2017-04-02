<?php

namespace AppBundle\Model;


use AppBundle\Entity\Device;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Ready extends AbstractState
{
    public function open(): Device
    {
        $this->device->setState('open');
        $this->device->setRemaining(0);
        return $this->device;
    }

    public function running(): Device
    {
        if (empty($this->device->getProgram())) {
            throw new HttpException(400);
        }
        $this->device->setState('running');
        $this->device->setRemaining(10);
        return $this->device;
    }


}