<?php

namespace Patgod85\Entity;


class Day
{
    /** @var  \DateTime */
    private $date;

    /** @var  string */
    private $type;

    /**
     * @param \DateTime $date
     * @param string $type
     */
    public function __construct(\DateTime $date, $type)
    {
        $this->date = $date;
        $this->type = $type;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

}