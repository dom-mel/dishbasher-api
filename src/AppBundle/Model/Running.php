<?php

namespace AppBundle\Model;


use AppBundle\Entity\Device;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Running extends DeviceState
{
    public function ready(): Device
    {
        if ($this->device->getState() === self::STATE_READY) {
            throw new HttpException(400);
        }
        $this->device->setRemaining(0);
        $this->device->setState(self::STATE_READY);
        return $this->device;
    }

}