<?php

namespace Patgod85\Provider;

use Patgod85\Entity\Day;
use Patgod85\Entity\Employee;

class Sheet
{
    /** @var  \PHPExcel_Worksheet */
    private $worksheet;

    /**
     * @param \PHPExcel_Worksheet $worksheet
     */
    public function __construct(\PHPExcel_Worksheet $worksheet)
    {
        $this->worksheet = $worksheet;
    }

    public function getMonth()
    {
        return $this->worksheet->getCell('C5')->getValue();
    }

    public function getYear()
    {
        return (int)$this->worksheet->getCell('C4')->getValue();
    }

    /**
     * @return Employee[]
     * @throws \PHPExcel_Exception
     */
    public function getEmployees()
    {
        /** @var int First employee row */
        $y = 10;

        /** @var int First day column */
        $x = 4;

        $employees = [];

        do
        {
            $name = $this->worksheet->getCellByColumnAndRow(1, $y)->getValue();

            if($name)
            {
                $employee = new Employee($name);

                for($i = 0; $i < 31; $i++)
                {
                    $dayProvider = \Patgod85\Provider\Day::getInstance(
                        $this->worksheet->getCellByColumnAndRow($x + $i, $y)
                    );

                    $type = $dayProvider->getType();

                    if(!in_array($type, ['1st shift 9 am to 6 pm', 'day off', 'public holiday']))
                    {

                        $date = $i+1;

                        $day = new Day(
                            \DateTime::createFromFormat('d-F-Y', "{$date}-{$this->getMonth()}-{$this->getYear()}"),
                            $type
                        );

                        $employee->addDay(
                            $day
                        );

                    }
                }

                $employees[] = $employee;
            }

            $y++;
        }
        while($name);

        return $employees;
    }
}