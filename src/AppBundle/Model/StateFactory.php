<?php

namespace AppBundle\Model;


use AppBundle\Entity\Device;
use Exception;

class StateFactory
{

    private static $validStates = [
        'ready' => Ready::class,
        'open' => Open::class,
        'running' => Running::class
    ];

    public static function createState(Device $device) : AbstractState
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