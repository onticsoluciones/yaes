#!/usr/bin/env php
<?php

use Symfony\Component\Console\Application;
require_once __DIR__ . '/vendor/autoload.php';

if(is_connected())
{
	

	date_default_timezone_set('Europe/Madrid');

	$loader = new \Ontic\Yaes\Loaders\SoftwarePackagesLoader();
	$softwarePackages = $loader->findSoftwarePackages(
    	__DIR__ . '/src/SoftwarePackages',
    	'Ontic\Yaes\SoftwarePackages'
	);

	setlocale(LC_ALL, getenv('LANG'));
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
}
else{
	echo "Please connect to the internet and try again\n";
}




function is_connected()
{
    $connected = @fsockopen("www.google.com", 80); 
                                        //website, port  (try 80 or 443)
    if ($connected){
        $is_conn = true; //action when connected
        fclose($connected);
    }else{
        $is_conn = false; //action in connection failure
    }
    return $is_conn;
}




