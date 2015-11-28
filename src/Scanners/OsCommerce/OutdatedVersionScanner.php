<?php

namespace Ontic\Yaes\Scanners\OsCommerce;

use Ontic\Yaes\Model\Target;
use Ontic\Yaes\Scanners\IScanner;

class OutdatedVersionScanner implements IScanner
{
    /**
     * @param Target $target
     * @return int
     */
    function getTargetStatus(Target $target)
    {
        return IScanner::STATUS_VULNERABLE;
    }

    /**
     * @return string
     */
    function getName()
    {
        return 'OutdatedVersion';
    }
}