<?php

namespace Patgod85\Provider;

use Patgod85\Entity\Day;
use Patgod85\Entity\Employee;

class Sheet
{
    /** @var  \PHPExcel_Worksheet */
    private $worksheet;

    /** @var Employee[] */
    private $employees;

    /** @var  \DateTime */
    private $publicHolidays;

    /**
     * @param \PHPExcel_Worksheet $worksheet
     */
    public function __construct(\PHPExcel_Worksheet $worksheet)
    {
        $this->worksheet = $worksheet;

        $this->parse();
    }

    private function parse()
    {
        /** @var int First employee row */
        $y = 10;

        /** @var int First day column */
        $x = 4;

        $this->employees = [];
        $this->publicHolidays = [];

        do
        {
            $name = $this->worksheet->getCellByColumnAndRow(1, $y)->getValue();

            if($name)
            {
                $employee = new Employee($name);

                for($i = 0; $i < 31; $i++)
                {
                    $date = $i+1;

                    $dateTime = \DateTime::createFromFormat('d-F-Y', "{$date}-{$this->getMonth()}-{$this->getYear()}");

                    if($dateTime->format('F') != $this->getMonth())
                    {
                        continue;
                    }

                    $dayProvider = \Patgod85\Provider\Day::getInstance(
                        $this->worksheet->getCellByColumnAndRow($x + $i, $y)
                    );

                    $type = $dayProvider->getType();


                    if(!in_array($type, ['', 'day off', 'public holiday']) && !$employee->getWorkStart())
                    {
                        $employee->setWorkStart($dateTime);
                    }

                    if($type == '' && $employee->getWorkStart())
                    {
                        $type = 'progul';
                    }

                    if(!in_array($type, ['', '1st shift 9 am to 6 pm', 'day off', 'public holiday']))
                    {
                        $day = new Day(
                            $dateTime,
                            $type
                        );

                        $employee->addDay(
                            $day
                        );
                    }
                    elseif($type == 'public holiday')
                    {
                        $this->publicHolidays[] = $dateTime;
                    }
                }

                $this->employees[] = $employee;
            }

            $y++;
        }
        while($name);
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
        return $this->employees;
    }

    /**
     * @return \DateTime
     */
    public function getPublicHolidays()
    {
        return $this->publicHolidays;
    }
}