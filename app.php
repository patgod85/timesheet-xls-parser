#!/usr/bin/env php
<?php

include __DIR__ . '/vendor/autoload.php';

use Patgod85\Process;
use Symfony\Component\Console\Application;

$application = new Application('timesheet-xls-parser', '1.0.0-alpha');
$application->add(new Process());
$application->run();