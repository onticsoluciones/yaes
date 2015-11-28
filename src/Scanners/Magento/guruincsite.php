<?php

namespace Ontic\Yaes\Scanners\Magento;

use Ontic\Yaes\Scanners\IScanner;
use Ontic\Yaes\Model\Target;

class Guruincsite implements IScanner
{
    /**
     * @param Target $target
     * @return int
     */
    function getTargetStatus(Target $target)
    {
        $url = sprintf('http://%s:%d/%sindex.php',
            $target->getHost(),
            $target->getPort(),
            $target->getBasePath());

        if (($responseBody = @file_get_contents($url)) === false)
        {
            return IScanner::STATUS_UNKNOWN;
        }

        if (strstr($responseBody, "LCWEHH(XHFER1){XHFER1=XHFER1"))
        {
            return IScanner::STATUS_VULNERABLE;
        }

        return IScanner::STATUS_SAFE;
    }

    /**
     * @return string
     */
    function getName()
    {
        return 'Guruincsite';
    }
}