<?php

namespace Ontic\Yaes\Output;

use Ontic\Yaes\Model\Target;
use Ontic\Yaes\Scanners\IScanner;
use Ontic\Yaes\SoftwarePackages\ISoftwarePackage;

class ConsoleOutput implements IOutput
{
    function writeSoftwareDetecionResult(Target $target, ISoftwarePackage $softwarePackage)
    {
        echo 'Detected software: ' . $softwarePackage->getName() . PHP_EOL;
    }

    function writeScanResult(Target $target, IScanner $scanner, $result)
    {
        switch($result)
        {
            case IScanner::STATUS_SAFE:
                $status = 'Safe';
                break;

            case IScanner::STATUS_VULNERABLE:
                $status = 'Vulnerable';
                break;

            case IScanner::STATUS_UNKNOWN:
            default:
                $status = 'Unknown';
        }

        echo $scanner->getName() . ' => ' . $status . PHP_EOL;
    }

    function finish()
    {
    }
}