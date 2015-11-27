<?php

namespace Ontic\Yaes\SoftwarePackages;

use Ontic\Yaes\Command\PrestashopScanCommand;
use Ontic\Yaes\Identifiers\IIdentifier;
use Ontic\Yaes\Identifiers\PrestashopIdentifier;
use Ontic\Yaes\Scanners\IScanner;
use Ontic\Yaes\Scanners\ScannerLoader;

class PrestashopSoftwarePackage implements ISoftwarePackage
{
    /**
     * @return string
     */
    function getCode()
    {
        return 'prestashop';
    }

    /**
     * @return string
     */
    function getName()
    {
        return 'Prestashop';
    }

    /**
     * @return IIdentifier
     */
    function getIdentifier()
    {
        return new PrestashopIdentifier();
    }

    /**
     * @return IScanner[]
     */
    function getScanners()
    {
        return (new ScannerLoader())->getScanners('Prestashop');
    }

    /**
     * @return mixed
     */
    function getCommands()
    {
        return [
            new PrestashopScanCommand()
        ];
    }
}