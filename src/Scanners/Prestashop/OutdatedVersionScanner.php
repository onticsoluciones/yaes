<?php

namespace Ontic\Yaes\Scanners\Prestashop;

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
        $url = sprintf('http://%s:%d/%stranslations/en.gzip',
            $target->getHost(),
            $target->getPort(),
            $target->getBasePath());

        if(($responseBody = @file_get_contents($url)) === false)
        {
            return IScanner::STATUS_UNKNOWN;
        }

        if(sha1($responseBody) === '5b8761cf0b33d64a49e4e39c7a6b29a0836ce7fb')
        {
            return IScanner::STATUS_SAFE;
        }

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