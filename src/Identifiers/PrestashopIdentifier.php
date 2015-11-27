<?php

namespace Ontic\Yaes\Identifiers;

use DOMDocument;
use Ontic\Yaes\Model\Target;

class PrestashopIdentifier implements IIdentifier
{
    /**
     * @param Target $target
     * @return int|null
     */
    function identify(Target $target)
    {
        $url = sprintf('http://%s:%d/%s',
            $target->getHost(),
            $target->getPort(),
            $target->getBasePath());

        if(($responseBody = @file_get_contents($url)) === false)
        {
            return IIdentifier::UNKNOWN;
        }

        $htmlDocument = new DOMDocument();
        @$htmlDocument->loadHTML($responseBody);

        foreach($htmlDocument->getElementsByTagName('link') as $meta)
        {
            if(strstr($meta->getAttribute('href'), 'blockcart.css') !== false)
            {
                return IIdentifier::PRESTASHOP;
            }
        }

        foreach($htmlDocument->getElementsByTagName('meta') as $meta)
        {
            if($meta->getAttribute('name') === 'generator' && strstr($meta->getAttribute('content'), 'PrestaShop') !== false)
            {
                return IIdentifier::PRESTASHOP;
            }
        }

        return IIdentifier::UNKNOWN;
    }
}