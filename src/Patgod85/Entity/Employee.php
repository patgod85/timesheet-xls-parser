<?php

namespace Patgod85\Entity;


class Employee
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var Day[]
     */
    private $days;

    /**
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = $name;
        $this->days = [];
    }

    public function addDay(Day $day)
    {
        $this->days[] = $day;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return Day[]
     */
    public function getDays()
    {
        return $this->days;
    }
}