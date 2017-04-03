<?php

namespace AppBundle\Model;


use AppBundle\Entity\Device;
use Symfony\Component\HttpKernel\Exception\HttpException;

abstract class DeviceState
{

    const STATE_READY = 'ready';
    const STATE_RUNNING = 'running';

    protected $device;

    public function __construct(Device $device)
    {
        $this->device = $device;
    }

    public function ready() : Device
    {
        throw new HttpException(400);
    }

    public function running() : Device
    {
        throw new HttpException(400);
    }

}