<?php

namespace AppBundle\Model;


use AppBundle\Entity\Device;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Ready extends DeviceState
{
    public function running(): Device
    {
        if (empty($this->device->getProgram())) {
            throw new HttpException(400);
        }
        if ($this->device->isDoorOpen()) {
            throw new HttpException(400);
        }
        $this->device->setState(self::STATE_RUNNING);
        $this->device->setRemaining(10);
        return $this->device;
    }

}