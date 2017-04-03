<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Device
{
    /**
     * @var string
     * @ORM\Column(type="string", length=128)
     * @ORM\Id
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="string", length=32)
     */
    private $state;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", length=32)
     */
    private $doorOpen;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $finishesAt;

    /**
     * @var string
     * @ORM\Column(type="string", length=32)
     */
    private $program;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param string $state
     */
    public function setState(string $state)
    {
        $this->state = $state;
    }


    /**
     * @return int
     */
    public function getFinishesAt(): int
    {
        return $this->finishesAt;
    }

    /**
     * @param int $finishesAt
     */
    public function setFinishesAt(int $finishesAt)
    {
        $this->finishesAt = $finishesAt;
    }

    /**
     * @return string
     */
    public function getProgram(): string
    {
        return $this->program;
    }

    /**
     * @param string $program
     */
    public function setProgram(string $program)
    {
        $this->program = $program;
    }

    /**
     * @return bool|null
     */
    public function isDoorOpen()
    {
        return $this->doorOpen;
    }

    /**
     * @param bool $doorOpen
     */
    public function setDoorOpen(bool $doorOpen)
    {
        $this->doorOpen = $doorOpen;
    }



}