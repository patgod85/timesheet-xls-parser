<?php

namespace Patgod85;


use Patgod85\Entity\Employee;
use Patgod85\Provider\Sheet;

class Parser
{
    /** @var \PHPExcel  */
    private $objPHPExcel;

    function __construct($filePath)
    {
        $inputFileName = __DIR__.'/../../'.$filePath;

        $this->objPHPExcel = \PHPExcel_IOFactory::load($inputFileName);
    }

    /**
     * @return Employee[]
     * @throws \PHPExcel_Exception
     */
    public function getEmployees()
    {
        /** @var Employee[] $employees */
        $employees = [];

        for($i = 0; $i < $this->objPHPExcel->getSheetCount(); $i++)
        {
            $provider = new Sheet($this->objPHPExcel->getSheet($i));

            print_r("process {$provider->getMonth()} {$provider->getYear()}");

            $_employees = $provider->getEmployees();

            foreach($_employees as $employee)
            {
                $index = $employee->getName().$employee->getSurname();

                if(!isset($employees[$index]))
                {
                    $employees[$index] = $employee;
                }
                else
                {
                    $employees[$index]->addDays($employee->getDays());
                }

            }
        }

        return $employees;
    }
}