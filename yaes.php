#!/usr/bin/env php
<?php

use Symfony\Component\Console\Application;

require_once __DIR__ . '/vendor/autoload.php';

date_default_timezone_set('Europe/Madrid');

$loader = new \Ontic\Yaes\Loaders\SoftwarePackagesLoader();
$softwarePackages = $loader->findSoftwarePackages(
    __DIR__ . '/src/SoftwarePackages',
    'Ontic\Yaes\SoftwarePackages'
);

$locale = 'es_ES';
putenv('LC_ALL=' . $locale);
setlocale(LC_ALL, $locale.'.UTF-8');
bindtextdomain("messages", "locale");
textdomain("messages");


$application = new Application();
foreach($softwarePackages as $package)
{
    $application->addCommands($package->getCommands());
}

$application->add(new \Ontic\Yaes\Command\IdentifyCommand($softwarePackages));
$application->add(new \Ontic\Yaes\Command\ScanCommand($softwarePackages));
$application->run();
