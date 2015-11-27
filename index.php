<?php

use Symfony\Component\Console\Application;

require_once __DIR__ . '/vendor/autoload.php';

$loader = new \Ontic\Yaes\Loaders\SoftwarePackagesLoader();
$softwarePackages = $loader->findSoftwarePackages(
    __DIR__ . '/src/SoftwarePackages',
    'Ontic\Yaes\SoftwarePackages'
);

$application = new Application();
foreach($softwarePackages as $package)
{
    $application->addCommands($package->getCommands());
}

$application->add(new \Ontic\Yaes\Command\IdentifyCommand($softwarePackages));
$application->run();
