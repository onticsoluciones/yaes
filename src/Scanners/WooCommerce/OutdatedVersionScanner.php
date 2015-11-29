<?php

namespace Ontic\Yaes\Scanners\WooCommerce;

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
        $url = $target->getUrl('wp-content/plugins/woocommerce/readme.txt');

        $key = 'Stable tag: ';
        $responseBody = @file_get_contents($url);
        if($responseBody === false)
        {
            return IScanner::STATUS_UNKNOWN;
        }

        $version = '';
        foreach(explode("\n", $responseBody) as $line)
        {
            if($this->startsWith($line, $key))
            {
                $version = str_replace($key, '', $line);
                break;
            }
        }

        if(version_compare($version, '2.4.10') === -1)
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
        return 'OutdatedVersion';
    }

    private function startsWith($haystack, $needle)
    {
        return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
    }

    /**
     * @return string
     */
    function getDescription()
    {
        return null;
    }
}