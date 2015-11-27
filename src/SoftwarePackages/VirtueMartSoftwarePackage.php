<?php

namespace Ontic\Yaes\SoftwarePackages;

use Ontic\Yaes\Identifiers\IIdentifier;
use Ontic\Yaes\Identifiers\VirtueMartIdentifier;
use Ontic\Yaes\Scanners\IScanner;
use Symfony\Component\Console\Command\Command;

class VirtueMartSoftwarePackage implements ISoftwarePackage
{
    /**
     * @return string
     */
    function getCode()
    {
        return 'virtuemart';
    }

    /**
     * @return string
     */
    function getName()
    {
        return 'VirtueMart';
    }

    /**
     * @return IIdentifier
     */
    function getIdentifier()
    {
        return new VirtueMartIdentifier($this);
    }

    /**
     * @return IScanner[]
     */
    function getScanners()
    {
        return [];
    }

    /**
     * @return Command[]
     */
    function getCommands()
    {
        return [];
    }
}