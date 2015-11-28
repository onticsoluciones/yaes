<?php

namespace Ontic\Yaes\Identifiers;

use Ontic\Yaes\Model\Target;
use Ontic\Yaes\SoftwarePackages\ISoftwarePackage;

interface IIdentifier
{
    /**
     * @param Target $target
     * @return ISoftwarePackage|null
     */
    function identify(Target $target);
}