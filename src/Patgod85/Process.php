<?php

namespace Patgod85;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Process extends Command
{
    protected function configure()
    {
        $definition = new InputDefinition();
        $definition->addArgument(new InputArgument('teamName', InputArgument::REQUIRED, 'Name of team'));
        $definition->addArgument(new InputArgument('xlsFilePath', InputArgument::REQUIRED, 'The path to file that will be parsed'));
        $definition->addArgument(new InputArgument('sqliteFilePath', InputArgument::REQUIRED, 'The path to database where data will be stored'));

        $this
            ->setName('process')
            ->setDescription('Parsing of XML-file with timesheet')
            ->setDefinition($definition);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $parser = new Parser($input->getArgument('xlsFilePath'));

        $repository = new Repository($input->getArgument('sqliteFilePath'));

        $repository->storeEmployees($input->getArgument('teamName'), $parser->getEmployees());

        print_r('Done');
    }

}