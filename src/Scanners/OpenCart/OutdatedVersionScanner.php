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
	$url_ok = $target->getUrl('admin/controller/fraud/ip.php');
	$status_ok = $this->getStatus($url_ok);

	$url_ko = $target->getUrl('admin/controller/module/amazon_button.php');
	$status_ko = $this->getStatus($url_ko);

        if(($status_ok == 200 || $status_ok == 500) && $status_ko == 404)
        {
            return IScanner::STATUS_SAFE; // 2.1.0.1 (20151128)
        }
	elseif($status_ok == 404 && $status_ko == 200)
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

    /**
     * Obtiene el último status code (tras redirects) al acceder a un fichero
     *
     * @return int final status code
     */
    function getStatus($url)
    {
	$headers = @get_headers($url, true);
        $value = NULL;
        if ($headers === false)
	{
	    return $headers;
        }
        foreach ($headers as $k => $v)
	{
	    if (!is_int($k))
	    {
		continue;
            }
            $value = $v;
        }
        return (int) substr($value, strpos($value, ' ', 8) + 1, 3);
     }

    /**
     * @return string
     */
    function getDescription()
    {
        return null;
    }
}