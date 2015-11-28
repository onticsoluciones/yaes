<?php

namespace Ontic\Yaes\Scanners\OpenCart;

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
        $url_ok = $target->getUrl('/admin/controller/fraud/ip.php');
	$url_ko = $target->getUrl('/admin/controller/module/amazon_button.php');

        $responseBody_ok = @file_get_contents($url_ok);
	$responseBody_ko = @file_get_contents($url_ko);

        if($responseBody_ok !== false && $responseBody_ko === false)
        {
            return IScanner::STATUS_SAFE; // 2.1.0.1 (20151128)
        }
	elseif($responseBody_ok === false && $responseBody_ko !== false)
	{
            return IScanner::STATUS_VULNERABLE;
        }
	else
	{
	    return IScanner::STATUS_UNKNOWN;
	}
    }

    /**
     * @return string
     */
    function getName()
    {
        return 'OutdatedVersion';
    }
}