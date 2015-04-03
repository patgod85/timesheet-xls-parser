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
        $definition->addArgument(new InputArgument('filePath', InputArgument::REQUIRED, 'The path to file that will be parsed'));

        $this
            ->setName('process')
            ->setDescription('Parsing of XML-file with timesheet')
            ->setDefinition($definition);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        new Parser($input, $output);
    }

}