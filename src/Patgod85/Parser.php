<?php

namespace Patgod85;


use Patgod85\Entity\Employee;
use Patgod85\Provider\Sheet;

class Parser
{
    /** @var \PHPExcel  */
    private $objPHPExcel;

    /** @var Employee[] */
    private $employees;

    /** @var  \DateTime */
    private $publicHolidays;

    function __construct($filePath)
    {
        $inputFileName = __DIR__.'/../../'.$filePath;

        $this->objPHPExcel = \PHPExcel_IOFactory::load($inputFileName);

        $this->parse();
    }

    private function parse()
    {
        $this->employees = [];
        $this->publicHolidays = [];

        for($i = 0; $i < $this->objPHPExcel->getSheetCount(); $i++)
        {
            $provider = new Sheet($this->objPHPExcel->getSheet($i));

            print_r("process {$provider->getMonth()} {$provider->getYear()}\n");

            $_employees = $provider->getEmployees();

            $this->publicHolidays = array_merge($this->publicHolidays, $provider->getPublicHolidays());

            foreach($_employees as $employee)
            {
                if(!$employee->getWorkStart())
                {
                    $employee->setWorkStart(\DateTime::createFromFormat('d-F-Y', "01-{$provider->getMonth()}-{$provider->getYear()}"));
                }

                $index = $employee->getName().$employee->getSurname();

                if(!isset($this->employees[$index]))
                {
                    $this->employees[$index] = $employee;
                }
                else
                {
                    $this->employees[$index]->addDays($employee->getDays());
                }
            }
        }
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
     * @return \DateTime[]
     */
    public function getPublicHolidays()
    {
        return $this->publicHolidays;
    }


}