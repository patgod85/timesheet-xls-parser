<?php

namespace Patgod85\Entity;


class Employee
{
    /** @var  int */
    private $id;

    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $surname;

    /**
     * @var Day[]
     */
    private $days;

    /** @var \DateTime */
    private $workStart;

    /**
     * @param $name
     * @throws \Exception
     */
    public function __construct($name)
    {
        $fullName = explode(' ', $name);

        if(count($fullName) != 2)
        {
            throw new \Exception('Invalid name format');
        }

        $this->name = $fullName[0];
        $this->surname = $fullName[1];
        $this->days = [];
    }

    public function addDay(Day $day)
    {
        $this->days[] = $day;
    }

    /**
     * @param Day[] $days
     */
    public function addDays($days)
    {
        $this->days += $days;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @return Day[]
     */
    public function getDays()
    {
        return $this->days;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return \DateTime
     */
    public function getWorkStart()
    {
        return $this->workStart;
    }

    /**
     * @param \DateTime $workStart
     */
    public function setWorkStart($workStart)
    {
        $this->workStart = $workStart;
    }


}