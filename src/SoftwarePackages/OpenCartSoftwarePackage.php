<?php

namespace Ontic\Yaes\SoftwarePackages;

use Ontic\Yaes\Identifiers\IIdentifier;
use Ontic\Yaes\Identifiers\OpenCartIdentifier;
use Ontic\Yaes\Scanners\IScanner;
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