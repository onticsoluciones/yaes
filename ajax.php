<?php

use Ontic\Yaes\Model\Target;
use Ontic\Yaes\Scanners\IScanner;
use Ontic\Yaes\SoftwarePackages\ISoftwarePackage;

require_once __DIR__ . '/vendor/autoload.php';

$loader = new \Ontic\Yaes\Loaders\SoftwarePackagesLoader();
$softwarePackages = $loader->findSoftwarePackages(
    __DIR__ . '/src/SoftwarePackages',
    'Ontic\Yaes\SoftwarePackages'
);

if(!isset($_GET['action']))
{
    dieWithStatus(400, 'Bad Request');
}

switch($_GET['action'])
{
    case 'identify':

        if(!isset($_GET['url']))
        {
            dieWithStatus(400, 'Bad Request');
        }

        $target = Target::createFromString($_GET['url']);

        $response = [];
        $software = identifySoftware($target);
        if($software === null)
        {
            $response['software'] = 'unknown';
        }
        else
        {
            $response['software'] = $software->getCode();
            $response['scanners'] = [];
            foreach($software->getScanners() as $scanner)
            {
                $response['scanners'][] = $scanner->getName();
            }
        }

        header('Content-Type: application/json', true, 200);
        echo json_encode($response);
        die;

    case 'scan':

        if(!isset($_GET['software']) || !isset($_GET['scanner']) || !isset($_GET['url']))
        {
            dieWithStatus(400, 'Bad Request');
        }

        $software = findSoftwareByCode($softwarePackages, $_GET['software']);
        if($software === null)
        {
            dieWithStatus(404, 'Not Found');
        }

        $scanner = findScannerByName($software->getScanners(), $_GET['scanner']);
        if($scanner === null)
        {
            dieWithStatus(404, 'Not Found');
        }

        $target = Target::createFromString($_GET['url']);
        $resultCode = $scanner->getTargetStatus($target);

        header('Content-Type: application/json', true, 200);
        echo json_encode(['status' => $resultCode]);
        die;
}

/**
 * @param ISoftwarePackage[] $softwarePackages
 * @param $code
 * @return ISoftwarePackage
 */
function findSoftwareByCode($softwarePackages, $code)
{
    foreach($softwarePackages as $softwarePackage)
    {
        if($code === $softwarePackage->getCode())
        {
            return $softwarePackage;
        }
    }

    return null;
}

/**
 * @param IScanner[] $scanners
 * @param string $name
 * @return IScanner|null
 */
function findScannerByName($scanners, $name)
{
    foreach($scanners as $scanner)
    {
        if($name === $scanner->getName())
        {
            return $scanner;
        }
    }

    return null;
}

/**
 * @param \Ontic\Yaes\Model\Target $target
 * @return ISoftwarePackage|null
 */
function identifySoftware(\Ontic\Yaes\Model\Target $target)
{
    global $softwarePackages;

    foreach($softwarePackages as $package)
    {
        $package = $package->getIdentifier()->identify($target);
        if($package !== false)
        {
            return $package;
        }
    }

    return null;
}

function dieWithStatus($code, $text)
{
    header('', true, $code);
    echo sprintf('%s %s', $code, $text);
    die;
}