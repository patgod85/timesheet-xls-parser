<?php

namespace Patgod85\Provider;


class Day
{
    /** @var  \PHPExcel_Cell */
    private $cell;

    /** @var  array */
    private $colours;

    /** @var Day */
    private static $instance;

    private function __construct()
    {
        $this->colours = [
            'FFFF00' => 'day off',
            '000000' => '1st shift 9 am to 6 pm',
            '339966' => 'otgul',
            'FF0000' => 'public holiday',
            '969696' => 'leave',
            '00CCFF' => 'progul',
            'FF6600' => 'paid holiday',
            '800080' => 'sick leave',
            'FF8080' => 'business trip',
        ];
    }

    /**
     * @param \PHPExcel_Cell $cell
     * @return Day
     */
    public static function getInstance(\PHPExcel_Cell $cell)
    {
        if(!self::$instance)
        {
            self::$instance = new self();
        }

        self::$instance->setCell($cell);

        return self::$instance;
    }

    public function setCell(\PHPExcel_Cell $cell)
    {
        $this->cell = $cell;
    }

    public function getColor()
    {
        return $this->cell->getStyle()->getFill()->getStartColor()->getRGB();
    }

    public function getTitle()
    {
        return trim($this->cell->getValue());
    }

    public function getType()
    {
        $preType = $this->colours[$this->getColor()];

        if($preType == '1st shift 9 am to 6 pm' && $this->getTitle() == "")
        {
            $preType = '';
        }
        elseif($preType == 'paid holiday' && $this->getTitle() == "1/ot")
        {
            $preType = 'paid holiday + half a day off';
        }

        return $preType;
    }
}