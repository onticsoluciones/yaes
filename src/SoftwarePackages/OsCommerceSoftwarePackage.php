<?php

namespace Ontic\Yaes\SoftwarePackages;

use Ontic\Yaes\Identifiers\IIdentifier;
use Ontic\Yaes\Identifiers\OsCommerceIdentifier;
use Ontic\Yaes\Scanners\IScanner;
use Symfony\Component\Console\Command\Command;

class OsCommerceSoftwarePackage implements ISoftwarePackage
{
    /**
     * @return string
     */
    function getCode()
    {
        return 'oscommerce';
    }

    /**
     * @return string
     */
    function getName()
    {
        return 'osCommerce';
    }

    /**
     * @return IIdentifier
     */
    function getIdentifier()
    {
        return new OsCommerceIdentifier($this);
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