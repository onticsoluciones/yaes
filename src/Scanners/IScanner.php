<?php

namespace Ontic\Yaes\Scanners;

use Ontic\Yaes\Model\Target;

interface IScanner
{
    const STATUS_UNKNOWN = -1;
    const STATUS_SAFE = 1;
    const STATUS_VULNERABLE = 2;

    /**
     * @param Target $target
     * @return int
     */
    function getTargetStatus(Target $target);

    /**
     * @return string
     */
    function getName();
}