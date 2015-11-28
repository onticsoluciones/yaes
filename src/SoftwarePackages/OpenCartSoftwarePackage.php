<?php

namespace Ontic\Yaes\SoftwarePackages;

use Ontic\Yaes\Identifiers\IIdentifier;
use Ontic\Yaes\Identifiers\OpenCartIdentifier;
use Ontic\Yaes\Scanners\IScanner;
use Ontic\Yaes\Scanners\ScannerLoader;
use Symfony\Component\Console\Command\Command;

class OpenCartSoftwarePackage implements ISoftwarePackage
{
    /**
     * @return string
     */
    function getCode()
    {
        return 'opencart';
    }

    /**
     * @return string
     */
    function getName()
    {
        return 'OpenCart';
    }

    /**
     * @return IIdentifier
     */
    function getIdentifier()
    {
        return new OpenCartIdentifier($this);
    }

    /**
     * @return IScanner[]
     */
    function getScanners()
    {
        return (new ScannerLoader())->getScanners('OpenCart');
    }

    /**
     * @return Command[]
     */
    function getCommands()
    {
        return [];
    }
}