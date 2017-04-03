<?php

namespace AppBundle\Model;


use AppBundle\Entity\Device;
use Exception;

class StateFactory
{

    private static $validStates = [
        DeviceState::STATE_READY => Ready::class,
        DeviceState::STATE_RUNNING => Running::class
    ];

    public static function createState(Device $device) : DeviceState
    {
        if (!self::validState($device)) {
            throw new Exception();
        }

        return new self::$validStates[$device->getState()]($device);
    }

    public static function validState(Device $device) : bool
    {
        return in_array($device->getState(), array_keys(self::$validStates));
    }

}