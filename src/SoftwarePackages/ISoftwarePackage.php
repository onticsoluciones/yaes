<?php

namespace Ontic\Yaes\SoftwarePackages;

use Ontic\Yaes\Identifiers\IIdentifier;
use Ontic\Yaes\Scanners\IScanner;
use Symfony\Component\Console\Command\Command;

interface ISoftwarePackage
{
    /**
     * @return string
     */
    function getCode();

    /**
     * @return string
     */
    function getName();

    /**
     * @return IIdentifier
     */
    function getIdentifier();

    /**
     * @return IScanner[]
     */
    function getScanners();

    /**
     * @return Command[]
     */
    function getCommands();
}