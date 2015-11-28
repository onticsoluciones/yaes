<?php

namespace Ontic\Yaes\Output;

use Ontic\Yaes\Model\Target;
use Ontic\Yaes\Scanners\IScanner;
use Ontic\Yaes\SoftwarePackages\ISoftwarePackage;

interface IOutput
{
    function writeSoftwareDetecionResult(Target $target, ISoftwarePackage $softwarePackage = null);

    function writeScanResult(Target $target, IScanner $scanner, $result);

    function finish();
}