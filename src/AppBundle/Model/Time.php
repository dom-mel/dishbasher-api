<?php

namespace AppBundle\Model;


class Time
{
    static $currentTime = null;

    public function time()
    {
        if (self::$currentTime === null) {
            return time();
        }
        return self::$currentTime;
    }
}