<?php

namespace Ontic\Yaes\SoftwarePackages;

use Ontic\Yaes\Command\MagentoScanCommand;
use Ontic\Yaes\Identifiers\IIdentifier;
use Ontic\Yaes\Identifiers\MagentoIdentifier;
use Ontic\Yaes\Scanners\IScanner;
use Ontic\Yaes\Scanners\ScannerLoader;

class MagentoSoftwarePackage implements ISoftwarePackage
{
    /**
     * @return string
     */
    function getCode()
    {
        return 'magento';
    }

    /**
     * @return string
     */
    function getName()
    {
        return 'Magento';
    }

    /**
     * @return IIdentifier
     */
    function getIdentifier()
    {
        return new MagentoIdentifier($this);
    }

    /**
     * @return IScanner[]
     */
    function getScanners()
    {
        return (new ScannerLoader())->getScanners('Magento');
    }

    /**
     * @return mixed
     */
    function getCommands()
    {
        return [];
    }
}