<?php

namespace Patgod85;


use Patgod85\Provider\Sheet;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Parser
{
    function __construct(InputInterface $input, OutputInterface $output)
    {
        $inputFileName = __DIR__.'/../../'.$input->getArgument('filePath');

        /** Load $inputFileName to a PHPExcel Object **/
        $objPHPExcel = \PHPExcel_IOFactory::load($inputFileName);

        $provider = new Sheet($objPHPExcel->getSheet($objPHPExcel->getSheetCount() - 1));



        print_r([
            $provider->getMonth(),
            $provider->getYear(),
            $provider->getEmployees()
        ]);






    }
}